<?php

declare(strict_types=1);

use EkAndreas\Resonator\Actions\CleanupOldMessages;
use EkAndreas\Resonator\Models\ResonatorAttachment;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;
use EkAndreas\Resonator\Tests\Fixtures\ResendMock;
use Saloon\Http\Faking\MockClient;

describe('CleanupOldMessages', function () {
    beforeEach(function () {
        config(['resonator.cleanup.enabled' => true]);
        config(['resonator.cleanup.days' => 30]);
    });

    it('deletes old threads from trash', function () {
        MockClient::global([
            '*' => ResendMock::deleteEmail(),
        ]);

        $trashFolder = ResonatorFolder::trash();

        // Old thread in trash (should be deleted)
        $oldThread = ResonatorThread::create([
            'folder_id' => $trashFolder->id,
            'subject' => 'Old Thread',
            'participant_email' => 'old@example.com',
            'updated_at' => now()->subDays(31),
        ]);

        ResonatorEmail::create([
            'thread_id' => $oldThread->id,
            'resend_id' => 're_old',
            'from_email' => 'old@example.com',
            'to' => ['inbox@example.com'],
            'subject' => 'Old Thread',
        ]);

        // Recent thread in trash (should NOT be deleted)
        $recentThread = ResonatorThread::create([
            'folder_id' => $trashFolder->id,
            'subject' => 'Recent Thread',
            'participant_email' => 'recent@example.com',
            'updated_at' => now()->subDays(5),
        ]);

        $result = (new CleanupOldMessages)->execute();

        expect($result['deleted_threads'])->toBe(1)
            ->and(ResonatorThread::find($oldThread->id))->toBeNull()
            ->and(ResonatorThread::find($recentThread->id))->not->toBeNull();
    });

    it('deletes old threads from spam', function () {
        MockClient::global([
            '*' => ResendMock::deleteEmail(),
        ]);

        $spamFolder = ResonatorFolder::spam();

        $oldSpam = ResonatorThread::create([
            'folder_id' => $spamFolder->id,
            'subject' => 'Old Spam',
            'participant_email' => 'spam@example.com',
            'updated_at' => now()->subDays(31),
        ]);

        ResonatorEmail::create([
            'thread_id' => $oldSpam->id,
            'resend_id' => 're_spam',
            'from_email' => 'spam@example.com',
            'to' => ['inbox@example.com'],
            'subject' => 'Spam',
        ]);

        $result = (new CleanupOldMessages)->execute();

        expect($result['deleted_threads'])->toBe(1);
    });

    it('does not delete threads from inbox', function () {
        $inboxFolder = ResonatorFolder::inbox();

        $oldInboxThread = ResonatorThread::create([
            'folder_id' => $inboxFolder->id,
            'subject' => 'Old but in inbox',
            'participant_email' => 'important@example.com',
            'updated_at' => now()->subDays(100),
        ]);

        $result = (new CleanupOldMessages)->execute();

        expect($result['deleted_threads'])->toBe(0)
            ->and(ResonatorThread::find($oldInboxThread->id))->not->toBeNull();
    });

    it('deletes attachments when thread is deleted', function () {
        MockClient::global([
            '*' => ResendMock::deleteEmail(),
        ]);

        $trashFolder = ResonatorFolder::trash();

        $thread = ResonatorThread::create([
            'folder_id' => $trashFolder->id,
            'subject' => 'With Attachments',
            'participant_email' => 'test@example.com',
            'updated_at' => now()->subDays(31),
        ]);

        $email = ResonatorEmail::create([
            'thread_id' => $thread->id,
            'resend_id' => 're_with_attachments',
            'from_email' => 'test@example.com',
            'to' => ['inbox@example.com'],
            'subject' => 'With Attachments',
        ]);

        ResonatorAttachment::create([
            'email_id' => $email->id,
            'filename' => 'document.pdf',
        ]);

        $result = (new CleanupOldMessages)->execute();

        expect($result['deleted_emails'])->toBe(1)
            ->and(ResonatorAttachment::count())->toBe(0);
    });

    it('uses custom days parameter', function () {
        MockClient::global([
            '*' => ResendMock::deleteEmail(),
        ]);

        $trashFolder = ResonatorFolder::trash();

        // Thread that is 10 days old
        $thread = ResonatorThread::create([
            'folder_id' => $trashFolder->id,
            'subject' => '10 days old',
            'participant_email' => 'test@example.com',
            'updated_at' => now()->subDays(10),
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'test@example.com',
            'to' => ['inbox@example.com'],
            'subject' => 'Test',
        ]);

        // With default 30 days, should NOT be deleted
        $result = (new CleanupOldMessages)->execute(30);
        expect($result['deleted_threads'])->toBe(0);

        // With 5 days, should be deleted
        $result = (new CleanupOldMessages)->execute(5);
        expect($result['deleted_threads'])->toBe(1);
    });

    it('returns early when cleanup is disabled', function () {
        config(['resonator.cleanup.enabled' => false]);

        $result = (new CleanupOldMessages)->execute();

        expect($result['deleted_threads'])->toBe(0)
            ->and($result['errors'])->toContain('Cleanup is disabled');
    });
});
