<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Jobs;

use EkAndreas\Resonator\Models\ResonatorContact;
use EkAndreas\Resonator\Models\ResonatorThread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Prism;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

class EnrichContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(
        protected ResonatorThread $thread
    ) {}

    public function handle(): void
    {
        if (! config('resonator.ai.enabled', true)) {
            return;
        }

        if (! config('resonator.contact_enrichment.enabled', true)) {
            return;
        }

        $contact = ResonatorContact::where('email', $this->thread->participant_email)->first();

        if (! $contact) {
            return;
        }

        // Skip if contact is already complete
        if ($contact->name && $contact->phone && $contact->company) {
            return;
        }

        try {
            $result = $this->extractContactInfo();

            $updateData = [];

            if (empty($contact->name) && ! empty($result['name'])) {
                $updateData['name'] = $result['name'];
            }

            if (empty($contact->phone) && ! empty($result['phone'])) {
                $updateData['phone'] = $result['phone'];
            }

            if (empty($contact->company) && ! empty($result['company'])) {
                $updateData['company'] = $result['company'];
            }

            if (! empty($updateData)) {
                $contact->update($updateData);

                Log::info('Resonator: Contact enriched', [
                    'contact_id' => $contact->id,
                    'email' => $contact->email,
                    'updated_fields' => array_keys($updateData),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Resonator: Contact enrichment failed', [
                'thread_id' => $this->thread->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function extractContactInfo(): array
    {
        $schema = new ObjectSchema(
            name: 'contact_info',
            description: 'Contact information extracted from emails',
            properties: [
                new StringSchema('name', 'Full name of the sender'),
                new StringSchema('phone', 'Phone number (Swedish format preferred)'),
                new StringSchema('company', 'Company or organization name'),
            ],
            requiredFields: []
        );

        $prompt = __('resonator::resonator.contact_enrichment.prompt');

        $emailContent = $this->buildEmailContent();

        $response = Prism::structured()
            ->using(config('resonator.ai.provider', 'openai'), config('resonator.ai.model', 'gpt-4o-mini'))
            ->withSchema($schema)
            ->withSystemPrompt($prompt)
            ->withPrompt($emailContent)
            ->generate();

        return $response->structured ?? [];
    }

    protected function buildEmailContent(): string
    {
        $maxEmails = config('resonator.contact_enrichment.max_emails_to_analyze', 3);
        $maxLength = config('resonator.contact_enrichment.max_text_length', 3000);

        $emails = $this->thread->emails()
            ->where('is_inbound', true)
            ->orderBy('created_at', 'desc')
            ->take($maxEmails)
            ->get();

        $content = [];

        foreach ($emails as $email) {
            $text = $email->text ?? strip_tags($email->html ?? '');
            $text = mb_substr($text, 0, $maxLength);

            $content[] = "---";
            $content[] = "From: {$email->from_display}";
            $content[] = "Subject: {$email->subject}";
            $content[] = "";
            $content[] = $text;
        }

        return implode("\n", $content);
    }
}
