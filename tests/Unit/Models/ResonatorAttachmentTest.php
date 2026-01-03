<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorAttachment;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;

describe('ResonatorAttachment', function () {
    beforeEach(function () {
        $thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Test',
            'participant_email' => 'test@example.com',
        ]);

        $this->email = ResonatorEmail::create([
            'thread_id' => $thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'Test',
        ]);
    });

    it('can create an attachment', function () {
        $attachment = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'resend_id' => 'att_123',
            'filename' => 'document.pdf',
            'content_type' => 'application/pdf',
            'size' => 125000,
        ]);

        expect($attachment)
            ->filename->toBe('document.pdf')
            ->content_type->toBe('application/pdf')
            ->size->toBe(125000);
    });

    it('has human readable size', function () {
        $attachment = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'filename' => 'large.zip',
            'size' => 1536000, // ~1.5 MB
        ]);

        expect($attachment->human_readable_size)->toContain('MB');
    });

    it('can detect image files', function () {
        $image = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'filename' => 'photo.jpg',
            'content_type' => 'image/jpeg',
        ]);

        $pdf = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'filename' => 'doc.pdf',
            'content_type' => 'application/pdf',
        ]);

        expect($image->isImage())->toBeTrue()
            ->and($pdf->isImage())->toBeFalse();
    });

    it('can detect PDF files', function () {
        $pdf = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'filename' => 'document.pdf',
            'content_type' => 'application/pdf',
        ]);

        $image = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'filename' => 'photo.jpg',
            'content_type' => 'image/jpeg',
        ]);

        expect($pdf->isPdf())->toBeTrue()
            ->and($image->isPdf())->toBeFalse();
    });

    it('belongs to email', function () {
        $attachment = ResonatorAttachment::create([
            'email_id' => $this->email->id,
            'filename' => 'test.txt',
        ]);

        expect($attachment->email->id)->toBe($this->email->id);
    });
});
