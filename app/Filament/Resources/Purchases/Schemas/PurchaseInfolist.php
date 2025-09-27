<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class PurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Information')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('N/A'),
                        TextEntry::make('supplier.name')
                            ->label('Supplier')
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
                        TextEntry::make('tax_percentage')
                            ->label('Tax Percentage')
                            ->suffix('%'),
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
