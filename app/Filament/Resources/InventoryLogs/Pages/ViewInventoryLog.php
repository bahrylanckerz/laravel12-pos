<?php

namespace App\Filament\Resources\InventoryLogs\Pages;

use App\Filament\Resources\InventoryLogs\InventoryLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInventoryLog extends ViewRecord
{
    protected static string $resource = InventoryLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
