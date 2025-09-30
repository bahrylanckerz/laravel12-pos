<?php

namespace App\Filament\Resources\ReturnSales\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReturnSales\ReturnSaleResource;

class ListReturnSales extends ListRecords
{
    protected static string $resource = ReturnSaleResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The return of sale has been deleted.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Return Sale'),
        ];
    }
}
