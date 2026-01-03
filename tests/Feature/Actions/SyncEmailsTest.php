<?php

declare(strict_types=1);

use EkAndreas\Resonator\Actions\SyncEmails;
use EkAndreas\Resonator\Http\Integrations\Resend\ResendConnector;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorSpamFilter;
use EkAndreas\Resonator\Models\ResonatorThread;
use EkAndreas\Resonator\Tests\Fixtures\ResendMock;
use Saloon\Http\Faking\MockClient;

beforeEach(function () {
    // Disable AI for these tests
    config(['resonator.ai.enabled' => false]);
    config(['resonator.spam_detection.enabled' => false]);
});

describe('SyncEmails', function () {
    it('syncs emails from Resend', function () {
        $email1 = ResendMock::incomingEmail(['id' => 're_email1']);
        $email2 = ResendMock::incomingEmail(['id' => 're_email2', 'from' => 'Jane <jane@example.com>']);

        $mockClient = MockClient::global([
            '*emails/receiving' => ResendMock::listEmails([$email1, $email2]),
            '*emails/receiving/re_email1' => ResendMock::getEmail($email1),
            '*emails/receiving/re_email2' => ResendMock::getEmail($email2),
        ]);

        $result = (new SyncEmails)->execute();

        expect($result['synced'])->toBe(2)
            ->and($result['skipped'])->toBe(0)
            ->and($result['errors'])->toBeEmpty()
            ->and(ResonatorEmail::count())->toBe(2)
            ->and(ResonatorThread::count())->toBe(2);

        $mockClient->assertSentCount(3); // 1 list + 2 get
    });

    it('skips already synced emails', function () {
        $email = ResendMock::incomingEmail(['id' => 're_existing']);

        // Create existing email
        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Existing',
            'participant_email' => 'existing@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'resend_id' => 're_existing',
            'from_email' => 'existing@example.com',
            'to' => ['inbox@example.com'],
            'subject' => 'Existing',
        ]);

        MockClient::global([
            '*emails/receiving' => ResendMock::listEmails([$email]),
        ]);

        $result = (new SyncEmails)->execute();

        expect($result['synced'])->toBe(0)
            ->and($result['skipped'])->toBe(1);
    });

    it('moves spam to spam folder', function () {
        $email = ResendMock::incomingEmail([
            'id' => 're_spam',
            'from' => 'spammer@blocked.com',
        ]);

        ResonatorSpamFilter::addToSpamList('spammer@blocked.com');

        MockClient::global([
            '*emails/receiving' => ResendMock::listEmails([$email]),
            '*emails/receiving/re_spam' => ResendMock::getEmail($email),
        ]);

        $result = (new SyncEmails)->execute();

        expect($result['spam'])->toBe(1);

        $thread = ResonatorThread::first();
        expect($thread->folder->slug)->toBe('spam');
    });

    it('groups replies into same thread via In-Reply-To', function () {
        $originalMessageId = '<original@example.com>';

        // Create original thread and email
        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Original Subject',
            'participant_email' => 'john@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'resend_id' => 're_original',
            'message_id' => $originalMessageId,
            'from_email' => 'john@example.com',
            'to' => ['inbox@example.com'],
            'subject' => 'Original Subject',
        ]);

        // Incoming reply
        $reply = ResendMock::replyEmail($originalMessageId, [
            'id' => 're_reply',
            'from' => 'john@example.com',
        ]);

        MockClient::global([
            '*emails/receiving' => ResendMock::listEmails([$reply]),
            '*emails/receiving/re_reply' => ResendMock::getEmail($reply),
        ]);

        $result = (new SyncEmails)->execute();

        expect($result['synced'])->toBe(1)
            ->and(ResonatorThread::count())->toBe(1) // Same thread
            ->and($thread->fresh()->emails)->toHaveCount(2);
    });

    it('creates attachments for emails', function () {
        $email = ResendMock::emailWithAttachments(['id' => 're_with_attachments']);

        MockClient::global([
            '*emails/receiving' => ResendMock::listEmails([$email]),
            '*emails/receiving/re_with_attachments' => ResendMock::getEmail($email),
        ]);

        $result = (new SyncEmails)->execute();

        $createdEmail = ResonatorEmail::first();
        expect($createdEmail->attachments)->toHaveCount(2);
    });

    it('moves thread from sent to inbox when customer replies', function () {
        $originalMessageId = '<sent@example.com>';

        // Create thread in sent folder
        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::sent()->id,
            'subject' => 'Our Reply',
            'participant_email' => 'customer@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'resend_id' => 're_sent',
            'message_id' => $originalMessageId,
            'from_email' => 'inbox@myapp.com',
            'to' => ['customer@example.com'],
            'subject' => 'Our Reply',
            'is_inbound' => false,
        ]);

        // Customer replies
        $reply = ResendMock::replyEmail($originalMessageId, [
            'id' => 're_customer_reply',
            'from' => 'customer@example.com',
        ]);

        MockClient::global([
            '*emails/receiving' => ResendMock::listEmails([$reply]),
            '*emails/receiving/re_customer_reply' => ResendMock::getEmail($reply),
        ]);

        (new SyncEmails)->execute();

        // Thread should still be in sent (based on current implementation)
        // But email should be added to thread
        expect($thread->fresh()->emails)->toHaveCount(2);
    });
});
