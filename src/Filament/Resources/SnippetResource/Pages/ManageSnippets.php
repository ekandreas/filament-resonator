<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources\SnippetResource\Pages;

use EkAndreas\Resonator\Filament\Resources\SnippetResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSnippets extends ManageRecords
{
    protected static string $resource = SnippetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
