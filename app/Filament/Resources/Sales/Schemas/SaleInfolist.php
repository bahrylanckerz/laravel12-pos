<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Information')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('N/A'),
                        TextEntry::make('customer.name')
                            ->label('Customer')
                            ->placeholder('N/A'),
                        TextEntry::make('paymentMethod.name')
                            ->label('Payment Method')
                            ->placeholder('N/A'),
                        TextEntry::make('date')
                            ->label('Date')
                            ->date(),
                        TextEntry::make('invoice_number')
                            ->label('Invoice Number'),
                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('IDR', true),
                        TextEntry::make('discount_amount')
                            ->label('Discount Amount')
                            ->money('IDR', true),
                        TextEntry::make('tax_amount')
                            ->label('Tax Amount')
                            ->money('IDR', true),
                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->money('IDR', true),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ])->columns(4),
            ])
            ->columns(1);
    }
}
