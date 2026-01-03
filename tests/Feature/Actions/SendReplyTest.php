<?php

declare(strict_types=1);

use EkAndreas\Resonator\Actions\SendReply;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();

    config([
        'resonator.mail.from_address' => 'support@example.com',
        'resonator.mail.from_name' => 'Support Team',
    ]);
});

describe('SendReply', function () {
    it('sends a reply to a thread', function () {
        $user = actingAsUser();

        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Customer Question',
            'participant_email' => 'customer@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'customer@example.com',
            'to' => ['support@example.com'],
            'subject' => 'Customer Question',
            'message_id' => '<original@example.com>',
            'is_inbound' => true,
        ]);

        $email = (new SendReply)->execute(
            thread: $thread,
            body: '<p>Thank you for your question. Here is the answer.</p>',
            user: $user
        );

        Mail::assertSent(function ($mail) {
            return $mail->hasTo('customer@example.com')
                && str_contains($mail->subject, 'Re:');
        });

        expect($email)
            ->is_inbound->toBeFalse()
            ->from_email->toBe('support@example.com');
    });

    it('moves thread to sent folder after reply', function () {
        $user = actingAsUser();

        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Question',
            'participant_email' => 'customer@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'customer@example.com',
            'to' => ['support@example.com'],
            'subject' => 'Question',
            'is_inbound' => true,
        ]);

        (new SendReply)->execute(
            thread: $thread,
            body: '<p>Reply content</p>',
            user: $user
        );

        expect($thread->fresh()->folder->slug)->toBe('sent');
    });

    it('marks thread as handled after reply', function () {
        $user = actingAsUser();

        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Question',
            'participant_email' => 'customer@example.com',
            'handled_by' => null,
            'handled_at' => null,
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'customer@example.com',
            'to' => ['support@example.com'],
            'subject' => 'Question',
            'is_inbound' => true,
        ]);

        (new SendReply)->execute(
            thread: $thread,
            body: '<p>Reply</p>',
            user: $user
        );

        $thread->refresh();

        expect($thread->handled_by)->toBe($user->id)
            ->and($thread->handled_at)->not->toBeNull();
    });

    it('includes signature in reply', function () {
        $user = actingAsUser(['name' => 'John Support']);

        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Question',
            'participant_email' => 'customer@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'customer@example.com',
            'to' => ['support@example.com'],
            'subject' => 'Question',
            'is_inbound' => true,
        ]);

        $email = (new SendReply)->execute(
            thread: $thread,
            body: '<p>Answer</p>',
            user: $user
        );

        expect($email->html)->toContain('John Support');
    });

    it('adds Re: prefix to subject if not present', function () {
        $user = actingAsUser();

        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Original Subject',
            'participant_email' => 'customer@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'customer@example.com',
            'to' => ['support@example.com'],
            'subject' => 'Original Subject',
            'is_inbound' => true,
        ]);

        $email = (new SendReply)->execute(
            thread: $thread,
            body: '<p>Reply</p>',
            user: $user
        );

        expect($email->subject)->toBe('Re: Original Subject');
    });

    it('does not duplicate Re: prefix', function () {
        $user = actingAsUser();

        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Re: Already Has Prefix',
            'participant_email' => 'customer@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'customer@example.com',
            'to' => ['support@example.com'],
            'subject' => 'Re: Already Has Prefix',
            'is_inbound' => true,
        ]);

        $email = (new SendReply)->execute(
            thread: $thread,
            body: '<p>Reply</p>',
            user: $user
        );

        expect($email->subject)->toBe('Re: Already Has Prefix');
    });
});
