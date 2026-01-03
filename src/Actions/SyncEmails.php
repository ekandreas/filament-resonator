<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Actions;

use EkAndreas\Resonator\Http\Integrations\Resend\Requests\GetReceivedEmailRequest;
use EkAndreas\Resonator\Http\Integrations\Resend\Requests\ListReceivedEmailsRequest;
use EkAndreas\Resonator\Http\Integrations\Resend\ResendConnector;
use EkAndreas\Resonator\Models\ResonatorAttachment;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorSpamFilter;
use EkAndreas\Resonator\Models\ResonatorThread;
use Illuminate\Support\Str;

class SyncEmails
{
    protected ResendConnector $connector;

    protected int $synced = 0;

    protected int $skipped = 0;

    protected int $spam = 0;

    protected array $errors = [];

    public function __construct()
    {
        $this->connector = new ResendConnector;
    }

    public function execute(): array
    {
        try {
            $response = $this->connector->send(new ListReceivedEmailsRequest(limit: 100));
            $emails = $response->json('data') ?? [];

            foreach ($emails as $emailData) {
                $this->processEmail($emailData);
            }
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        return [
            'synced' => $this->synced,
            'skipped' => $this->skipped,
            'spam' => $this->spam,
            'errors' => $this->errors,
        ];
    }

    protected function processEmail(array $emailData): void
    {
        $resendId = $emailData['id'] ?? null;

        if (! $resendId) {
            return;
        }

        // Check if already synced
        if (ResonatorEmail::where('resend_id', $resendId)->exists()) {
            $this->skipped++;

            return;
        }

        try {
            // Get full email details
            $detailResponse = $this->connector->send(new GetReceivedEmailRequest($resendId));
            $emailDetails = $detailResponse->json();

            $this->createEmailFromDetails($emailDetails);
            $this->synced++;
        } catch (\Exception $e) {
            $this->errors[] = "Failed to process email {$resendId}: " . $e->getMessage();
        }
    }

    protected function createEmailFromDetails(array $details): void
    {
        $fromEmail = $details['from'] ?? '';
        $fromName = $this->extractNameFromEmail($fromEmail);
        $subject = $details['subject'] ?? '(No Subject)';

        // Determine target folder
        $folder = $this->determineFolder($fromEmail);

        // Find or create thread
        $thread = $this->findOrCreateThread($details, $folder, $fromEmail, $fromName, $subject);

        // Create email record
        $email = ResonatorEmail::create([
            'thread_id' => $thread->id,
            'resend_id' => $details['id'],
            'message_id' => $details['message_id'] ?? null,
            'in_reply_to' => $details['in_reply_to'] ?? null,
            'references' => $details['references'] ?? null,
            'is_inbound' => true,
            'from_email' => $this->extractEmailAddress($fromEmail),
            'from_name' => $fromName,
            'to' => $this->normalizeRecipients($details['to'] ?? []),
            'cc' => $this->normalizeRecipients($details['cc'] ?? []),
            'bcc' => $this->normalizeRecipients($details['bcc'] ?? []),
            'reply_to' => $details['reply_to'] ?? null,
            'subject' => $subject,
            'html' => $details['html'] ?? null,
            'text' => $details['text'] ?? null,
            'headers' => $details['headers'] ?? null,
            'sent_at' => isset($details['created_at']) ? \Carbon\Carbon::parse($details['created_at']) : now(),
        ]);

        // Create attachments
        $this->createAttachments($email, $details['attachments'] ?? []);

        // Update thread
        $thread->update([
            'last_message_at' => $email->sent_at ?? now(),
            'is_read' => false,
        ]);

        // Track spam
        if ($folder->slug === 'spam') {
            $this->spam++;
        }

        // Move from sent/archive back to inbox if customer replied
        $this->handleCustomerReply($thread, $folder);
    }

    protected function determineFolder(string $fromEmail): ResonatorFolder
    {
        $email = $this->extractEmailAddress($fromEmail);

        // Check spam list
        if (ResonatorSpamFilter::isSpam($email)) {
            return ResonatorFolder::spam() ?? ResonatorFolder::inbox();
        }

        // Validate email format
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ResonatorFolder::spam() ?? ResonatorFolder::inbox();
        }

        return ResonatorFolder::inbox();
    }

    protected function findOrCreateThread(array $details, ResonatorFolder $folder, string $fromEmail, ?string $fromName, string $subject): ResonatorThread
    {
        $participantEmail = $this->extractEmailAddress($fromEmail);
        $normalizedSubject = $this->normalizeSubject($subject);

        // Try to find by In-Reply-To header
        if (! empty($details['in_reply_to'])) {
            $existingEmail = ResonatorEmail::where('message_id', $details['in_reply_to'])->first();
            if ($existingEmail) {
                return $existingEmail->thread;
            }
        }

        // Try to find by subject + participant within last X days
        $matchDays = config('resonator.threading.subject_match_days', 30);
        $existingThread = ResonatorThread::where('participant_email', $participantEmail)
            ->where('subject', 'like', '%' . $normalizedSubject . '%')
            ->where('created_at', '>=', now()->subDays($matchDays))
            ->latest('last_message_at')
            ->first();

        if ($existingThread) {
            return $existingThread;
        }

        // Create new thread
        return ResonatorThread::create([
            'folder_id' => $folder->id,
            'subject' => $subject,
            'participant_email' => $participantEmail,
            'participant_name' => $fromName,
            'is_read' => false,
            'is_starred' => false,
            'last_message_at' => now(),
        ]);
    }

    protected function createAttachments(ResonatorEmail $email, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            ResonatorAttachment::create([
                'email_id' => $email->id,
                'resend_id' => $attachment['id'] ?? null,
                'filename' => $attachment['filename'] ?? 'unknown',
                'content_type' => $attachment['content_type'] ?? null,
                'content_disposition' => $attachment['content_disposition'] ?? 'attachment',
                'content_id' => $attachment['content_id'] ?? null,
                'size' => $attachment['size'] ?? null,
            ]);
        }
    }

    protected function handleCustomerReply(ResonatorThread $thread, ResonatorFolder $targetFolder): void
    {
        // If customer replied and thread is in sent/archive, move to inbox
        $currentFolder = $thread->folder;

        if (in_array($currentFolder->slug, ['sent', 'archive']) && $targetFolder->slug === 'inbox') {
            $thread->update(['folder_id' => $targetFolder->id]);
        }

        // Don't move from trash
    }

    protected function extractEmailAddress(string $from): string
    {
        if (preg_match('/<(.+)>/', $from, $matches)) {
            return strtolower(trim($matches[1]));
        }

        return strtolower(trim($from));
    }

    protected function extractNameFromEmail(string $from): ?string
    {
        if (preg_match('/^(.+?)\s*</', $from, $matches)) {
            return trim($matches[1], ' "\'');
        }

        return null;
    }

    protected function normalizeSubject(string $subject): string
    {
        // Remove Re:, Fwd:, Sv:, Vs:, etc.
        return preg_replace('/^(Re|Fwd|Sv|Vs|Aw|Antw):\s*/i', '', trim($subject));
    }

    protected function normalizeRecipients($recipients): array
    {
        if (is_string($recipients)) {
            return [$recipients];
        }

        return is_array($recipients) ? $recipients : [];
    }
}
