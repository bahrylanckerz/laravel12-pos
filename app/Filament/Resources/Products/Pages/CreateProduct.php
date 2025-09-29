<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\InventoryLog;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Products\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The product has been created.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function afterCreate(): void
    {
        $product = $this->record;

        if ($product->stock > 0) {
            $dataLog = [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'change_type' => 'in',
                'quantity_change' => $product->stock,
                'notes' => 'Initial stock on product creation',
            ];
            InventoryLog::create($dataLog);
        }
    }
}
