<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Tests\Fixtures;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class ResendMock
{
    public static function fake(array $responses = []): MockClient
    {
        return new MockClient(array_merge([
            '*' => MockResponse::make(['data' => []], 200),
        ], $responses));
    }

    public static function listEmails(array $emails = []): MockResponse
    {
        return MockResponse::make([
            'data' => $emails,
        ], 200);
    }

    public static function getEmail(array $data = []): MockResponse
    {
        return MockResponse::make(array_merge([
            'id' => 're_' . uniqid(),
            'from' => 'sender@example.com',
            'to' => ['recipient@example.com'],
            'subject' => 'Test Subject',
            'html' => '<p>Test content</p>',
            'text' => 'Test content',
            'created_at' => now()->toIso8601String(),
            'message_id' => '<' . uniqid() . '@example.com>',
            'attachments' => [],
        ], $data), 200);
    }

    public static function getAttachment(): MockResponse
    {
        return MockResponse::make([
            'download_url' => 'https://example.com/download/attachment.pdf',
        ], 200);
    }

    public static function sendEmail(): MockResponse
    {
        return MockResponse::make([
            'id' => 're_' . uniqid(),
        ], 200);
    }

    public static function deleteEmail(): MockResponse
    {
        return MockResponse::make([
            'deleted' => true,
        ], 200);
    }

    public static function error(int $status = 500, string $message = 'Server error'): MockResponse
    {
        return MockResponse::make([
            'error' => $message,
        ], $status);
    }

    /**
     * Create a realistic incoming email payload
     */
    public static function incomingEmail(array $overrides = []): array
    {
        $id = 're_' . uniqid();

        return array_merge([
            'id' => $id,
            'from' => 'John Doe <john@example.com>',
            'to' => ['inbox@myapp.com'],
            'cc' => [],
            'bcc' => [],
            'subject' => 'Test inquiry',
            'html' => '<p>Hello, I would like to know more about your services.</p><p>Best regards,<br>John Doe<br>+46 70 123 4567<br>Acme Corp</p>',
            'text' => "Hello, I would like to know more about your services.\n\nBest regards,\nJohn Doe\n+46 70 123 4567\nAcme Corp",
            'message_id' => "<{$id}@mail.example.com>",
            'in_reply_to' => null,
            'references' => null,
            'created_at' => now()->toIso8601String(),
            'attachments' => [],
        ], $overrides);
    }

    /**
     * Create a reply email payload
     */
    public static function replyEmail(string $inReplyTo, array $overrides = []): array
    {
        return self::incomingEmail(array_merge([
            'subject' => 'Re: Test inquiry',
            'in_reply_to' => $inReplyTo,
            'references' => $inReplyTo,
            'html' => '<p>Thank you for your quick response!</p>',
            'text' => 'Thank you for your quick response!',
        ], $overrides));
    }

    /**
     * Create a spam-like email payload
     */
    public static function spamEmail(array $overrides = []): array
    {
        return self::incomingEmail(array_merge([
            'from' => 'Newsletter <noreply@marketing.example.com>',
            'subject' => '🎉 Special offer just for you! 50% off everything!',
            'html' => '<h1>AMAZING DEALS!</h1><p>Click here to claim your exclusive discount!</p><p>Unsubscribe: click here</p>',
            'text' => 'AMAZING DEALS! Click here to claim your exclusive discount!',
        ], $overrides));
    }

    /**
     * Create an email with attachments
     */
    public static function emailWithAttachments(array $overrides = []): array
    {
        return self::incomingEmail(array_merge([
            'attachments' => [
                [
                    'id' => 'att_' . uniqid(),
                    'filename' => 'document.pdf',
                    'content_type' => 'application/pdf',
                    'size' => 125000,
                ],
                [
                    'id' => 'att_' . uniqid(),
                    'filename' => 'image.jpg',
                    'content_type' => 'image/jpeg',
                    'size' => 250000,
                ],
            ],
        ], $overrides));
    }
}
