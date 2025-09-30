<?php

namespace App\Filament\Resources\ReturnPurchases\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ReturnPurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Return of Purchase Information')
                    ->schema([
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('-'),
                        TextEntry::make('purchase.invoice_number')
                            ->label('Purchase Invoice Number')
                            ->placeholder('-'),
                        TextEntry::make('supplier.name')
                            ->label('Supplier')
                            ->placeholder('-'),
                        TextEntry::make('paymentMethod.name')
                            ->label('Payment Method')
                            ->placeholder('-'),
                        TextEntry::make('total_return')
                            ->label('Total Return')
                            ->money('IDR', true),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime()
                            ->placeholder('-'),
                    ])->columns(2),
            ])
            ->columns(1);
    }
}
