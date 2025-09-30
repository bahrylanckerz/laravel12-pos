<?php

namespace App\Filament\Resources\ReturnSales\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ReturnSaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Return of Sale Information')
                    ->schema([
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('-'),
                        TextEntry::make('sale.invoice_number')
                            ->label('Sale Invoice Number')
                            ->placeholder('-'),
                        TextEntry::make('customer.name')
                            ->label('Customer')
                            ->placeholder('-'),
                        TextEntry::make('paymentMethod.name')
                            ->label('Payment Method')
                            ->placeholder('-'),
                        TextEntry::make('total_refund')
                            ->label('Total Refund')
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
