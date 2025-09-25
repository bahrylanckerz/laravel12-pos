<?php

namespace App\Filament\Resources\Sales\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\Sales\SaleResource;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Delete Successfully!')
            ->body('The sale has been deleted.')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'cash' => Tab::make()->query(fn($query) => $query->whereHas('paymentMethod', fn($query) => $query->where('name', 'Cash')))->label('Cash'),
            'credit_card' => Tab::make()->query(fn($query) => $query->whereHas('paymentMethod', fn($query) => $query->where('name', 'Credit Card')))->label('Credit Card'),
            'bank_transfer' => Tab::make()->query(fn($query) => $query->whereHas('paymentMethod', fn($query) => $query->where('name', 'Bank Transfer')))->label('Bank Transfer'),
            'e_wallet' => Tab::make()->query(fn($query) => $query->whereHas('paymentMethod', fn($query) => $query->where('name', 'E-Wallet')))->label('E-Wallet'),
        ];
    }
}
