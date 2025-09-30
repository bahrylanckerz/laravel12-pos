<?php

namespace App\Filament\Resources\ReturnSales\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ReturnSales\ReturnSaleResource;

class ReturnSaleDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'ReturnSaleDetails';

    protected static ?string $relatedResource = ReturnSaleResource::class;

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
