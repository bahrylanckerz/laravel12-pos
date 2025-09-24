<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\Supplier;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->schema([
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->exists(table: 'suppliers', column: 'id')
                            ->columnSpanFull(),
                        Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->exists(table: 'categories', column: 'id'),
                        Select::make('sub_category_id')
                            ->label('Sub Category')
                            ->options(function (callable $get) {
                                $category = Category::find($get('category_id'));
                                if (!$category) {
                                    return [];
                                }
                                return $category->subCategories->pluck('name', 'id');
                            })
                            ->disabled(fn(callable $get) => $get('category_id') === null)
                            ->dehydrated()
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->exists(table: 'sub_categories', column: 'id'),
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
                        FileUpload::make('image')
                            ->label('Product Image')
                            ->image()
                            ->nullable()
                            ->maxSize(1024)
                            ->directory('product')
                            ->visibility('public')
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
                            ->default(0)
                            ->minValue(0)
                            ->step(1),
                    ])->columns(2),
            ])
            ->columns(1);
    }
}
