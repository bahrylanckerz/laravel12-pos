<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Sales\SaleResource;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The sale has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function afterCreate(): void
    {
        $sale = $this->record;

        $sale->load('saleDetails');

        foreach ($sale->saleDetails as $item) {
            DB::table('products')->where('id', $item->product_id)->decrement('stock', $item->quantity);

            $dataLog = [
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'change_type' => 'out',
                'quantity_change' => $item->quantity,
                'notes' => 'Sale',
            ];
            InventoryLog::create($dataLog);
        }
    }
}
