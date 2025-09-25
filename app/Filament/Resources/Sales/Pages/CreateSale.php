<?php

namespace App\Filament\Resources\Sales\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Sales\SaleResource;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The sale has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }
}
