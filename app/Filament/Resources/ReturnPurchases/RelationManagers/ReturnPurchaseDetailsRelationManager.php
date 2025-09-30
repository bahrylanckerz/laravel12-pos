<?php

namespace App\Filament\Resources\ReturnPurchases\RelationManagers;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\RelationManagers\RelationManager;

class ReturnPurchaseDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'ReturnPurchaseDetails';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('product.image')
                    ->label('Image')
                    ->size(50),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->placeholder('N/A')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('notes')
                    ->placeholder('-'),
            ])
            ->recordActions([
                // ViewAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
