<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorAttachment;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;

describe('ResonatorEmail', function () {
    beforeEach(function () {
        $this->thread = ResonatorThread::create([
            'folder_id' => ResonatorFolder::inbox()->id,
            'subject' => 'Test Thread',
            'participant_email' => 'test@example.com',
        ]);
    });

    it('can create an email', function () {
        $email = ResonatorEmail::create([
            'thread_id' => $this->thread->id,
            'resend_id' => 're_123',
            'from_email' => 'sender@example.com',
            'from_name' => 'Sender Name',
            'to' => ['recipient@example.com'],
            'subject' => 'Test Subject',
            'html' => '<p>Test content</p>',
            'text' => 'Test content',
            'is_inbound' => true,
        ]);

        expect($email)
            ->resend_id->toBe('re_123')
            ->from_email->toBe('sender@example.com')
            ->is_inbound->toBeTrue();
    });

    it('has from_display attribute', function () {
        $email = ResonatorEmail::create([
            'thread_id' => $this->thread->id,
            'from_email' => 'sender@example.com',
            'from_name' => 'John Doe',
            'to' => ['recipient@example.com'],
            'subject' => 'Test',
        ]);

        expect($email->from_display)->toBe('John Doe <sender@example.com>');
    });

    it('has from_display without name', function () {
        $email = ResonatorEmail::create([
            'thread_id' => $this->thread->id,
            'from_email' => 'sender@example.com',
            'from_name' => null,
            'to' => ['recipient@example.com'],
            'subject' => 'Test',
        ]);

        expect($email->from_display)->toBe('sender@example.com');
    });

    it('has preview attribute', function () {
        $email = ResonatorEmail::create([
            'thread_id' => $this->thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'Test',
            'text' => 'This is a test email with some content that should be truncated in the preview',
        ]);

        $preview = $email->preview;

        expect($preview)->toContain('This')
            ->and(str_word_count($preview))->toBeLessThanOrEqual(10);
    });

    it('has attachments relationship', function () {
        $email = ResonatorEmail::create([
            'thread_id' => $this->thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'Test',
        ]);

        ResonatorAttachment::create([
            'email_id' => $email->id,
            'filename' => 'document.pdf',
            'content_type' => 'application/pdf',
            'size' => 12345,
        ]);

        expect($email->attachments)->toHaveCount(1)
            ->and($email->hasAttachments())->toBeTrue();
    });

    it('casts to and cc as arrays', function () {
        $email = ResonatorEmail::create([
            'thread_id' => $this->thread->id,
            'from_email' => 'sender@example.com',
            'to' => ['one@example.com', 'two@example.com'],
            'cc' => ['cc@example.com'],
            'subject' => 'Test',
        ]);

        expect($email->to)->toBeArray()->toHaveCount(2)
            ->and($email->cc)->toBeArray()->toHaveCount(1);
    });
});
