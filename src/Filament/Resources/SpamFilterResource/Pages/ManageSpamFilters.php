<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources\SpamFilterResource\Pages;

use EkAndreas\Resonator\Filament\Resources\SpamFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSpamFilters extends ManageRecords
{
    protected static string $resource = SpamFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
