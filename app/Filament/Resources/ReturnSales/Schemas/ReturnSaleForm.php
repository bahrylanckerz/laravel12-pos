<?php

namespace App\Filament\Resources\ReturnSales\Schemas;

use App\Models\Sale;
use App\Models\SaleDetail;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class ReturnSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
                        DatePicker::make('date')
                            ->native(false)
                            ->default(now())
                            ->required(),
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->native(false)
                            ->default(Auth::id())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Select::make('sale_id')
                            ->label('Sale Invoice Number')
                            ->relationship('sale', 'invoice_number')
                            ->native(false)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $sale = Sale::find($state);

                                    if ($sale) {
                                        $set('customer_id', $sale->customer_id);
                                    }
                                } else {
                                    $set('customer_id', null);
                                }
                            }),
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->native(false)
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Textarea::make('notes')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Group::make()
                    ->schema([
                        Section::make('Return Sale Details')
                            ->schema([
                                Repeater::make('returnSaleDetails')
                                    ->label('Items')
                                    ->relationship()
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->relationship('product', 'name', function ($query, callable $get) {
                                                $saleId = $get('../../sale_id');

                                                if ($saleId) {
                                                    $query->whereIn('products.id', function ($sub) use ($saleId) {
                                                        $sub->select('product_id')
                                                            ->from('sale_details')
                                                            ->where('sale_id', $saleId);
                                                    });
                                                } else {
                                                    $query->whereNull('products.id');
                                                }
                                            })
                                            ->preload()
                                            ->reactive()
                                            ->searchable()
                                            ->required()
                                            ->columnSpanFull()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->afterStateUpdated(
                                                function (callable $set, $get, $state) {
                                                    $saleId = $get('../../sale_id');

                                                    if ($saleId && $state) {
                                                        $detail = SaleDetail::where('sale_id', $saleId)
                                                            ->where('product_id', $state)
                                                            ->first();

                                                        if ($detail) {
                                                            $set('price', $detail->price);
                                                            $set('subtotal', $detail->price * ($get('quantity') ?? 1));
                                                        }
                                                    }

                                                    $items = $get('../../returnSaleDetails') ?? [];
                                                    $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                                    $set('../../total_refund', $total);
                                                }
                                            ),
                                        TextInput::make('price')
                                            ->label('Price')
                                            ->prefix('IDR')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        TextInput::make('quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->reactive()
                                            ->maxValue(function (callable $get) {
                                                $saleId = $get('../../sale_id');
                                                $productId = $get('product_id');

                                                if ($saleId && $productId) {
                                                    $detail = SaleDetail::where('sale_id', $saleId)
                                                        ->where('product_id', $productId)
                                                        ->first();

                                                    return $detail?->quantity ?? 1;
                                                }

                                                return 1;
                                            })
                                            ->afterStateUpdated(function (callable $set, $get, $state) {
                                                $price = $get('price') ?? 0;
                                                $subtotal = $price * $state;
                                                $set('subtotal', $subtotal);

                                                $items = $get('../../returnSaleDetails') ?? [];
                                                $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                                $set('../../total_refund', $total);
                                            }),
                                        TextInput::make('subtotal')
                                            ->label('Subtotal')
                                            ->prefix('IDR')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        Textarea::make('notes')
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])
                                    ->defaultItems(1)
                                    ->minItems(1)
                                    ->columnSpanFull()
                                    ->required()
                                    ->columns(3),
                            ])
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('Payment Information')
                            ->schema([
                                Select::make('payment_method_id')
                                    ->label('Payment Method')
                                    ->relationship('paymentMethod', 'name')
                                    ->native(false)
                                    ->default(1)
                                    ->required(),
                                TextInput::make('total_refund')
                                    ->label('Total Refund')
                                    ->prefix('IDR')
                                    ->default(0)
                                    ->numeric()
                                    ->required(),
                            ])
                    ])
            ])
            ->columns(3);
    }
}
