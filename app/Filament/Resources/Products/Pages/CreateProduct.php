<?php

namespace App\Filament\Resources\Products\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Products\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The product has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }
}
