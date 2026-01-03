<?php

declare(strict_types=1);

namespace EkAndreas\Resonator;

use EkAndreas\Resonator\Commands\SyncInboxCommand;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ResonatorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'resonator';

    public static string $viewNamespace = 'resonator';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasMigrations([
                'create_resonator_folders_table',
                'create_resonator_threads_table',
                'create_resonator_emails_table',
                'create_resonator_attachments_table',
                'create_resonator_snippets_table',
                'create_resonator_spam_filters_table',
                'create_resonator_contacts_table',
            ])
            ->hasTranslations()
            ->hasCommand(SyncInboxCommand::class);
    }

    public function packageBooted(): void
    {
        // Register any CSS/JS assets here if needed
        // FilamentAsset::register([
        //     Css::make('resonator', __DIR__ . '/../resources/dist/resonator.css'),
        // ], 'elseif-ab/filament-resonator');
    }

    public function packageRegistered(): void
    {
        //
    }
}
