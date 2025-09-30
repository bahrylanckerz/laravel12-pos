<?php

namespace App\Filament\Resources\ReturnPurchases\Pages;

use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReturnPurchases\ReturnPurchaseResource;

class CreateReturnPurchase extends CreateRecord
{
    protected static string $resource = ReturnPurchaseResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The return of purchase has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function afterCreate(): void
    {
        $purchase = $this->record;

        $purchase->load('returnPurchaseDetails');

        foreach ($purchase->returnPurchaseDetails as $item) {
            DB::table('products')->where('id', $item->product_id)->decrement('stock', $item->quantity);

            $dataLog = [
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'change_type' => 'out',
                'quantity_change' => $item->quantity,
                'notes' => 'Return of Purchase',
            ];
            InventoryLog::create($dataLog);
        }
    }
}
