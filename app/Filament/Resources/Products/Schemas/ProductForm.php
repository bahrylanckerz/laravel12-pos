<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\SubCategory;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Get;

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
                            ->columnSpanFull()
                            ->createOptionForm([
                                Group::make([
                                    TextInput::make('name')
                                        ->required()
                                        ->columnSpanFull(),
                                    TextInput::make('email')
                                        ->label('Email Address')
                                        ->unique(ignoreRecord: true)
                                        ->email()
                                        ->required(),
                                    TextInput::make('phone')
                                        ->label('Phone Number')
                                        ->numeric()
                                        ->required(),
                                    Textarea::make('address')
                                        ->columnSpanFull(),
                                ])->columns(2),
                            ])
                            ->createOptionUsing(function ($data) {
                                return Supplier::create($data)->getKey();
                            })
                            ->reactive(),
                        Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->exists(table: 'categories', column: 'id')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->maxLength(65535),
                            ])
                            ->createOptionUsing(function ($data) {
                                return Category::create($data)->getKey();
                            })
                            ->reactive(),
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
                            ->required()
                            ->exists(table: 'sub_categories', column: 'id')
                            ->createOptionForm(function (callable $get) {
                                return [
                                    Select::make('category_id')
                                        ->label('Category')
                                        ->options(Category::all()->pluck('name', 'id'))
                                        ->default($get('category_id'))
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),
                                    TextInput::make('name')
                                        ->required(),
                                    Toggle::make('is_active')
                                        ->label('Active')
                                        ->default(true)
                                        ->required(),
                                ];
                            })
                            ->createOptionUsing(function ($data) {
                                return SubCategory::create($data)->getKey();
                            })
                            ->reactive(),
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
