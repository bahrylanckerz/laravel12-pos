<?php

namespace App\Filament\Resources\Sales\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Sales\SaleResource;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The sale has been updated.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            // DeleteAction::make(),
        ];
    }
}
