<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Commands;

use EkAndreas\Resonator\Actions\CleanupOldMessages;
use EkAndreas\Resonator\Actions\SyncEmails;
use Illuminate\Console\Command;

class SyncInboxCommand extends Command
{
    protected $signature = 'resonator:sync
                            {--no-cleanup : Skip cleanup of old messages}
                            {--cleanup-days= : Number of days for cleanup (default from config)}';

    protected $description = 'Sync emails from Resend and cleanup old messages';

    public function handle(): int
    {
        $this->info('Starting email sync...');

        // Sync emails
        $syncResult = (new SyncEmails)->execute();

        $this->info("Sync complete:");
        $this->line("  - Synced: {$syncResult['synced']}");
        $this->line("  - Skipped: {$syncResult['skipped']}");
        $this->line("  - Spam: {$syncResult['spam']}");

        if (! empty($syncResult['errors'])) {
            $this->warn("Errors during sync:");
            foreach ($syncResult['errors'] as $error) {
                $this->line("  - {$error}");
            }
        }

        // Cleanup old messages
        if (! $this->option('no-cleanup') && config('resonator.cleanup.enabled', true)) {
            $this->newLine();
            $this->info('Starting cleanup...');

            $days = $this->option('cleanup-days')
                ? (int) $this->option('cleanup-days')
                : config('resonator.cleanup.days', 30);

            $cleanupResult = (new CleanupOldMessages)->execute($days);

            $this->info("Cleanup complete:");
            $this->line("  - Threads deleted: {$cleanupResult['deleted_threads']}");
            $this->line("  - Emails deleted: {$cleanupResult['deleted_emails']}");
            $this->line("  - Deleted from Resend: {$cleanupResult['deleted_from_resend']}");

            if (! empty($cleanupResult['errors'])) {
                $this->warn("Errors during cleanup:");
                foreach ($cleanupResult['errors'] as $error) {
                    $this->line("  - {$error}");
                }
            }
        }

        $this->newLine();
        $this->info('Done!');

        return self::SUCCESS;
    }
}
