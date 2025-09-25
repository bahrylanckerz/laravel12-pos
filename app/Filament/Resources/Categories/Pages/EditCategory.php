<?php

namespace App\Filament\Resources\Categories\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Categories\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The category has been updated.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
