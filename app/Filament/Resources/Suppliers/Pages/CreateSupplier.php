<?php

namespace App\Filament\Resources\Suppliers\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Suppliers\SupplierResource;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The supplier has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }
}
