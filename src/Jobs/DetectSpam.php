<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Jobs;

use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Prism;
use Prism\Prism\Schema\BooleanSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

class DetectSpam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 60;

    public function __construct(
        protected ResonatorEmail $email
    ) {}

    public function handle(): void
    {
        if (! config('resonator.ai.enabled', true)) {
            return;
        }

        if (! config('resonator.spam_detection.enabled', true)) {
            return;
        }

        $thread = $this->email->thread;

        // Skip if already in spam folder
        if ($thread->folder?->slug === 'spam') {
            return;
        }

        try {
            $result = $this->detectSpam();

            if ($result['is_spam'] === true) {
                $thread->moveToSpam();

                Log::info('Resonator: Email marked as spam', [
                    'thread_id' => $thread->id,
                    'email_id' => $this->email->id,
                    'from' => $this->email->from_email,
                    'reason' => $result['reason'] ?? 'Unknown',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Resonator: Spam detection failed', [
                'email_id' => $this->email->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function detectSpam(): array
    {
        $schema = new ObjectSchema(
            name: 'spam_detection',
            description: 'Spam detection result',
            properties: [
                new BooleanSchema('is_spam', 'Whether the email is spam'),
                new StringSchema('reason', 'Brief reason for the classification'),
            ],
            requiredFields: ['is_spam']
        );

        $prompt = __('resonator::resonator.spam_detection.prompt');

        $emailContent = $this->buildEmailContent();

        $response = Prism::structured()
            ->using(config('resonator.ai.provider', 'openai'), config('resonator.ai.model', 'gpt-4o-mini'))
            ->withSchema($schema)
            ->withSystemPrompt($prompt)
            ->withPrompt("Analyze this email:\n\n{$emailContent}")
            ->generate();

        return $response->structured ?? ['is_spam' => false, 'reason' => null];
    }

    protected function buildEmailContent(): string
    {
        $content = [];
        $content[] = "From: {$this->email->from_display}";
        $content[] = "Subject: {$this->email->subject}";
        $content[] = '';
        $content[] = $this->email->text ?? strip_tags($this->email->html ?? '');

        return implode("\n", $content);
    }
}
