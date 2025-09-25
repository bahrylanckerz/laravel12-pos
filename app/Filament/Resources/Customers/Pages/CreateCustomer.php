<?php

namespace App\Filament\Resources\Customers\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Customers\CustomerResource;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The customer has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }
}
