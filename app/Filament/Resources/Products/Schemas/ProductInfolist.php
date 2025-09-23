<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Components\TextEntry;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->schema([
                        ImageColumn::make('image')
                            ->label('Image')
                            ->size(100),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('supplier.name')
                            ->label('Supplier'),
                        TextEntry::make('name')
                            ->label('Product Name'),
                        TextEntry::make('barcode'),
                        TextEntry::make('description')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('price')
                            ->money('IDR', true),
                        TextEntry::make('stock')
                            ->numeric(),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->placeholder('-')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->placeholder('-')
                            ->dateTime(),
                    ])->columns(2),
            ])
            ->columns(1);
    }
}
