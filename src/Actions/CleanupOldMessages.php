<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Actions;

use EkAndreas\Resonator\Http\Integrations\Resend\Requests\DeleteReceivedEmailRequest;
use EkAndreas\Resonator\Http\Integrations\Resend\ResendConnector;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;

class CleanupOldMessages
{
    protected ResendConnector $connector;

    protected int $deletedThreads = 0;

    protected int $deletedEmails = 0;

    protected int $deletedFromResend = 0;

    protected array $errors = [];

    public function __construct()
    {
        $this->connector = new ResendConnector;
    }

    public function execute(?int $days = null): array
    {
        $days = $days ?? config('resonator.cleanup.days', 30);

        if (! config('resonator.cleanup.enabled', true)) {
            return [
                'deleted_threads' => 0,
                'deleted_emails' => 0,
                'deleted_from_resend' => 0,
                'errors' => ['Cleanup is disabled'],
            ];
        }

        $cutoffDate = now()->subDays($days);

        // Get threads in trash and spam that are older than cutoff
        $trashFolder = ResonatorFolder::trash();
        $spamFolder = ResonatorFolder::spam();

        $folderIds = array_filter([
            $trashFolder?->id,
            $spamFolder?->id,
        ]);

        if (empty($folderIds)) {
            return [
                'deleted_threads' => 0,
                'deleted_emails' => 0,
                'deleted_from_resend' => 0,
                'errors' => ['No trash or spam folders found'],
            ];
        }

        $threads = ResonatorThread::whereIn('folder_id', $folderIds)
            ->where('updated_at', '<', $cutoffDate)
            ->get();

        foreach ($threads as $thread) {
            $this->deleteThread($thread);
        }

        return [
            'deleted_threads' => $this->deletedThreads,
            'deleted_emails' => $this->deletedEmails,
            'deleted_from_resend' => $this->deletedFromResend,
            'errors' => $this->errors,
        ];
    }

    protected function deleteThread(ResonatorThread $thread): void
    {
        try {
            // Delete from Resend
            foreach ($thread->emails as $email) {
                if ($email->resend_id) {
                    try {
                        $this->connector->send(new DeleteReceivedEmailRequest($email->resend_id));
                        $this->deletedFromResend++;
                    } catch (\Exception $e) {
                        // Log but continue - email might already be deleted from Resend
                    }
                }

                // Delete attachments
                $email->attachments()->delete();
                $this->deletedEmails++;
            }

            // Delete all emails
            $thread->emails()->delete();

            // Delete contacts relationship
            $thread->contacts()->detach();

            // Delete thread
            $thread->delete();
            $this->deletedThreads++;
        } catch (\Exception $e) {
            $this->errors[] = "Failed to delete thread {$thread->id}: " . $e->getMessage();
        }
    }
}
