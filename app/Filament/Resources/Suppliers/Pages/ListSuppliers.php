<?php

namespace App\Filament\Resources\Suppliers\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Suppliers\SupplierResource;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The supplier has been deleted.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
