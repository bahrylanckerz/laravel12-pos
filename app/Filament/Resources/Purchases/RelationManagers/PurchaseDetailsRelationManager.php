<?php

namespace App\Filament\Resources\Purchases\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class PurchaseDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseDetails';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
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
