<?php

namespace App\Filament\Resources\SubCategories\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SubCategories\SubCategoryResource;

class CreateSubCategory extends CreateRecord
{
    protected static string $resource = SubCategoryResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The sub category has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }
}
