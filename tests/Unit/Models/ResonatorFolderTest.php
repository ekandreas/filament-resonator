<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;

describe('ResonatorFolder', function () {
    it('can create a folder', function () {
        $folder = ResonatorFolder::create([
            'name' => 'Custom Folder',
            'slug' => 'custom',
            'icon' => 'heroicon-o-folder',
            'color' => 'primary',
        ]);

        expect($folder)
            ->name->toBe('Custom Folder')
            ->slug->toBe('custom')
            ->is_system->toBeFalse();
    });

    it('has system folders seeded', function () {
        expect(ResonatorFolder::inbox())->not->toBeNull()
            ->and(ResonatorFolder::sent())->not->toBeNull()
            ->and(ResonatorFolder::archive())->not->toBeNull()
            ->and(ResonatorFolder::spam())->not->toBeNull()
            ->and(ResonatorFolder::trash())->not->toBeNull();
    });

    it('can count unread threads', function () {
        $folder = ResonatorFolder::inbox();

        // Create read and unread threads
        ResonatorThread::create([
            'folder_id' => $folder->id,
            'subject' => 'Read thread',
            'participant_email' => 'read@example.com',
            'is_read' => true,
        ]);

        ResonatorThread::create([
            'folder_id' => $folder->id,
            'subject' => 'Unread thread 1',
            'participant_email' => 'unread1@example.com',
            'is_read' => false,
        ]);

        ResonatorThread::create([
            'folder_id' => $folder->id,
            'subject' => 'Unread thread 2',
            'participant_email' => 'unread2@example.com',
            'is_read' => false,
        ]);

        expect($folder->unreadCount())->toBe(2);
    });

    it('has threads relationship', function () {
        $folder = ResonatorFolder::inbox();

        $thread = ResonatorThread::create([
            'folder_id' => $folder->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        expect($folder->threads)->toHaveCount(1)
            ->and($folder->threads->first()->id)->toBe($thread->id);
    });
});
