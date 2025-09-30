<?php

namespace App\Filament\Resources\ReturnSales\Pages;

use App\Filament\Resources\ReturnSales\ReturnSaleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReturnSale extends ViewRecord
{
    protected static string $resource = ReturnSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
