<?php

namespace App\Filament\Resources\Customers\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Customers\CustomerResource;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The customer has been deleted.')
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
