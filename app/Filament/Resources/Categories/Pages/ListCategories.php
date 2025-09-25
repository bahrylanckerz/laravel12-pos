<?php

namespace App\Filament\Resources\Categories\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Categories\CategoryResource;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The category has been deleted.')
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
