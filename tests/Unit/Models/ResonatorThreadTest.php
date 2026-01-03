<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorContact;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;

describe('ResonatorThread', function () {
    beforeEach(function () {
        $this->inbox = ResonatorFolder::inbox();
    });

    it('can create a thread', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test Subject',
            'participant_email' => 'test@example.com',
            'participant_name' => 'Test User',
        ]);

        expect($thread)
            ->subject->toBe('Test Subject')
            ->participant_email->toBe('test@example.com')
            ->is_read->toBeFalse()
            ->is_starred->toBeFalse();
    });

    it('creates a contact when thread is created', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'newcontact@example.com',
            'participant_name' => 'New Contact',
        ]);

        $contact = ResonatorContact::where('email', 'newcontact@example.com')->first();

        expect($contact)->not->toBeNull()
            ->and($contact->name)->toBe('New Contact')
            ->and($thread->contacts)->toHaveCount(1);
    });

    it('can toggle star', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
            'is_starred' => false,
        ]);

        $thread->toggleStar();

        expect($thread->fresh()->is_starred)->toBeTrue();

        $thread->toggleStar();

        expect($thread->fresh()->is_starred)->toBeFalse();
    });

    it('can mark as read and unread', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
            'is_read' => false,
        ]);

        $thread->markAsRead();
        expect($thread->fresh()->is_read)->toBeTrue();

        $thread->markAsUnread();
        expect($thread->fresh()->is_read)->toBeFalse();
    });

    it('can be archived', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        $thread->archive();

        expect($thread->fresh()->folder->slug)->toBe('archive');
    });

    it('can be moved to trash', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        $thread->moveToTrash();

        expect($thread->fresh()->folder->slug)->toBe('trash');
    });

    it('can be moved to spam', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        $thread->moveToSpam();

        expect($thread->fresh()->folder->slug)->toBe('spam');
    });

    it('can be marked as handled', function () {
        $user = createUser();

        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        $thread->markAsHandled($user);

        expect($thread->fresh())
            ->handled_by->toBe($user->id)
            ->handled_at->not->toBeNull();
    });

    it('has emails relationship', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'Test',
        ]);

        expect($thread->emails)->toHaveCount(1);
    });

    it('can get latest email', function () {
        $thread = ResonatorThread::create([
            'folder_id' => $this->inbox->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'First email',
            'created_at' => now()->subHour(),
        ]);

        $latest = ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'Latest email',
            'created_at' => now(),
        ]);

        expect($thread->latestEmail->id)->toBe($latest->id);
    });
});
