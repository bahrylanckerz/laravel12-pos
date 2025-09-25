<?php

namespace App\Filament\Resources\Products\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Products\ProductResource;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The product has been deleted.')
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
