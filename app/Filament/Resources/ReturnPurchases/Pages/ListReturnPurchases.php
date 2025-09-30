<?php

namespace App\Filament\Resources\ReturnPurchases\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReturnPurchases\ReturnPurchaseResource;

class ListReturnPurchases extends ListRecords
{
    protected static string $resource = ReturnPurchaseResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The return of purchase has been deleted.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Return Purchase'),
        ];
    }
}
