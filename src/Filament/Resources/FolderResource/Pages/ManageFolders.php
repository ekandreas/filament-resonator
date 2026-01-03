<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources\FolderResource\Pages;

use EkAndreas\Resonator\Filament\Resources\FolderResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFolders extends ManageRecords
{
    protected static string $resource = FolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
