<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Purchases\PurchaseResource;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The purchase has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function afterCreate(): void
    {
        $purchase = $this->record;

        $purchase->load('purchaseDetails');

        foreach ($purchase->purchaseDetails as $item) {
            DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);

            $dataLog = [
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'change_type' => 'in',
                'quantity_change' => $item->quantity,
                'notes' => 'Purchase',
            ];
            InventoryLog::create($dataLog);
        }
    }
}
