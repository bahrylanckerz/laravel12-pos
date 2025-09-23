<?php

namespace App\Filament\Resources\Sales\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Sales\SaleResource;
use Filament\Resources\RelationManagers\RelationManager;

class SaleDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'SaleDetails';

    protected static ?string $relatedResource = SaleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('discount_item')
                    ->label('Discount Item')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', true)
                    ->sortable(),
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
