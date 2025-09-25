<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The user has been updated.')
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
