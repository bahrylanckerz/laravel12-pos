<?php

namespace App\Filament\Resources\ReturnPurchases\Pages;

use App\Filament\Resources\ReturnPurchases\ReturnPurchaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReturnPurchase extends ViewRecord
{
    protected static string $resource = ReturnPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
