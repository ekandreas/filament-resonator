<?php

declare(strict_types=1);

namespace EkAndreas\Resonator;

use EkAndreas\Resonator\Filament\Resources\FolderResource;
use EkAndreas\Resonator\Filament\Resources\InboxResource;
use EkAndreas\Resonator\Filament\Resources\SnippetResource;
use EkAndreas\Resonator\Filament\Resources\SpamFilterResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ResonatorPlugin implements Plugin
{
    protected bool $hasFolders = true;

    protected bool $hasSnippets = true;

    protected bool $hasSpamFilters = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'resonator';
    }

    public function register(Panel $panel): void
    {
        $resources = [
            InboxResource::class,
        ];

        if ($this->hasFolders) {
            $resources[] = FolderResource::class;
        }

        if ($this->hasSnippets) {
            $resources[] = SnippetResource::class;
        }

        if ($this->hasSpamFilters) {
            $resources[] = SpamFilterResource::class;
        }

        $panel->resources($resources);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function folders(bool $condition = true): static
    {
        $this->hasFolders = $condition;

        return $this;
    }

    public function snippets(bool $condition = true): static
    {
        $this->hasSnippets = $condition;

        return $this;
    }

    public function spamFilters(bool $condition = true): static
    {
        $this->hasSpamFilters = $condition;

        return $this;
    }

    public function hasFolders(): bool
    {
        return $this->hasFolders;
    }

    public function hasSnippets(): bool
    {
        return $this->hasSnippets;
    }

    public function hasSpamFilters(): bool
    {
        return $this->hasSpamFilters;
    }
}
