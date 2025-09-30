<?php

namespace App\Filament\Resources\ReturnSales\Pages;

use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReturnSales\ReturnSaleResource;

class CreateReturnSale extends CreateRecord
{
    protected static string $resource = ReturnSaleResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The return of sale has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function afterCreate(): void
    {
        $sale = $this->record;

        $sale->load('returnSaleDetails');

        foreach ($sale->returnSaleDetails as $item) {
            DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);

            $dataLog = [
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'change_type' => 'in',
                'quantity_change' => $item->quantity,
                'notes' => 'Return of Sale',
            ];
            InventoryLog::create($dataLog);
        }
    }
}
