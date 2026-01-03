<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Actions;

use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;
use Illuminate\Support\Facades\Mail;

class SendReply
{
    public function execute(
        ResonatorThread $thread,
        string $body,
        ?string $subject = null,
        array $attachments = [],
        $user = null
    ): ResonatorEmail {
        $user = $user ?? auth()->user();
        $fromAddress = config('resonator.mail.from_address');
        $fromName = config('resonator.mail.from_name');

        // Build subject with reply prefix
        $originalSubject = $thread->subject;
        $replySubject = $subject ?? $this->buildReplySubject($originalSubject);

        // Get the email to reply to
        $latestEmail = $thread->latestEmail;
        $replyTo = $latestEmail?->from_email ?? $thread->participant_email;

        // Build signature
        $signature = $this->buildSignature($user);
        $fullBody = $body . $signature;

        // Build headers for threading
        $headers = [];
        if ($latestEmail?->message_id) {
            $headers['In-Reply-To'] = $latestEmail->message_id;
            $headers['References'] = $latestEmail->references
                ? $latestEmail->references . ' ' . $latestEmail->message_id
                : $latestEmail->message_id;
        }

        // Send via Laravel Mail
        Mail::html($fullBody, function ($message) use ($replyTo, $replySubject, $fromAddress, $fromName, $headers, $attachments) {
            $message->from($fromAddress, $fromName)
                ->to($replyTo)
                ->subject($replySubject);

            foreach ($headers as $name => $value) {
                $message->getHeaders()->addTextHeader($name, $value);
            }

            foreach ($attachments as $attachment) {
                if (isset($attachment['path'])) {
                    $message->attach($attachment['path'], [
                        'as' => $attachment['name'] ?? null,
                        'mime' => $attachment['mime'] ?? null,
                    ]);
                }
            }
        });

        // Create email record
        $email = ResonatorEmail::create([
            'thread_id' => $thread->id,
            'is_inbound' => false,
            'from_email' => $fromAddress,
            'from_name' => $fromName,
            'to' => [$replyTo],
            'subject' => $replySubject,
            'html' => $fullBody,
            'text' => strip_tags($fullBody),
            'sent_at' => now(),
        ]);

        // Move to sent folder and mark as handled
        if ($sentFolder = ResonatorFolder::sent()) {
            $thread->moveToFolder($sentFolder);
        }
        $thread->markAsHandled($user);
        $thread->update(['last_message_at' => now()]);

        return $email;
    }

    protected function buildReplySubject(string $originalSubject): string
    {
        // Check if already has a reply prefix
        if (preg_match('/^(Re|Sv|Aw|Antw):\s*/i', $originalSubject)) {
            return $originalSubject;
        }

        return 'Re: ' . $originalSubject;
    }

    protected function buildSignature($user): string
    {
        $regards = __('resonator::resonator.signature.regards');
        $name = $user?->name ?? config('resonator.mail.from_name');

        $signature = "<br><br>// {$regards},<br>{$name}";

        if ($phone = $user?->phone ?? null) {
            $signature .= ", {$phone}";
        }

        return $signature;
    }
}
