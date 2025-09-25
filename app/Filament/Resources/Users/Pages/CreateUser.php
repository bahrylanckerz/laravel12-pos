<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The user has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }
}
