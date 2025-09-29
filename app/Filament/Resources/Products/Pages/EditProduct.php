<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\InventoryLog;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Save Successfully!')
            ->body('The product has been updated.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $product = Product::find($data['id']);

        $currentStock = $product->stock;
        $changeStock  = $data['stock'];

        if ($data['stock'] > 0 && $currentStock != $changeStock) {
            $type     = $changeStock > $currentStock ? 'in' : 'out';
            $quantity = abs($changeStock - $currentStock);

            $dataLog = [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'change_type' => $type,
                'quantity_change' => $quantity,
                'notes' => 'Stock changes when product updated',
            ];
            InventoryLog::create($dataLog);
        }

        return $data;
    }
}
