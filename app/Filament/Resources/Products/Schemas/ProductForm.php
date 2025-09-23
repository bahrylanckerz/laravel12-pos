<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\Supplier;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->exists(table: 'categories', column: 'id'),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->exists(table: 'suppliers', column: 'id'),
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('barcode')
                            ->label('Barcode')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        MarkdownEditor::make('description')
                            ->label('Description')
                            ->nullable()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label('Price')
                            ->prefix('IDR ')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('stock')
                            ->label('Stock')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(1),
                    ])->columns(2),
            ])
            ->columns(1);
    }
}
