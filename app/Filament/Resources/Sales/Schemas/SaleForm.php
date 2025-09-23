<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Customer;
use App\Models\Product;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->hiddenLabel()
                            ->prefix('User:'),
                        DatePicker::make('date')
                            ->native(false)
                            ->default(now())
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->hiddenLabel()
                            ->prefix('Date:'),
                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->default(fn() => 'INV-' . strtoupper(uniqid()))
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->unique(ignoreRecord: true)
                            ->hiddenLabel()
                            ->prefix('Invoice:'),
                    ])
                    ->columns(3),
                Section::make('Customer Information')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->preload()
                            ->reactive()
                            ->searchable()
                            ->required()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $customer = Customer::find($state);
                                $set('email', $customer?->email);
                                $set('phone', $customer?->phone);
                                $set('address', $customer?->address);
                            }),
                        Select::make('payment_method_id')
                            ->label('Payment Method')
                            ->relationship('paymentMethod', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255)
                            ->nullable()
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->maxLength(255)
                            ->nullable()
                            ->disabled(),
                        Textarea::make('address')
                            ->label('Address')
                            ->maxLength(65535)
                            ->rows(3)
                            ->nullable()
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Order Details')
                    ->schema([
                        Repeater::make('saleDetails')
                            ->label('Items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->preload()
                                    ->reactive()
                                    ->searchable()
                                    ->required()
                                    ->columnSpanFull()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(function (callable $set, $get, $state) {
                                        $product = Product::find($state);
                                        $set('price', $product?->price ?? 0);
                                        $quantity = $get('quantity') ?? 1;
                                        $discountItem = ($get('discount_item') ?? 0) * $quantity;
                                        $subtotal = ($product?->price ?? 0) * $quantity - $discountItem;
                                        $set('subtotal', $subtotal);

                                        $items = $get('../../saleDetails') ?? [];
                                        $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_amount', $total);
                                        $discount = $get('../../discount_amount') ?? 0;
                                        $tax = $get('../../tax_amount') ?? 0;
                                        $grandTotal = $total - $discount + $tax;
                                        $set('../../grand_total', $grandTotal);
                                    }),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $get, $state) {
                                        $price = $get('price') ?? 0;
                                        $discountItem = ($get('discount_item') ?? 0) * $state;
                                        $subtotal = $price * $state - $discountItem;
                                        $set('subtotal', $subtotal);

                                        $items = $get('../../saleDetails') ?? [];
                                        $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_amount', $total);
                                        $discount = $get('../../discount_amount') ?? 0;
                                        $tax = $get('../../tax_amount') ?? 0;
                                        $grandTotal = $total - $discount + $tax;
                                        $set('../../grand_total', $grandTotal);
                                    }),
                                TextInput::make('price')
                                    ->label('Price')
                                    ->prefix('IDR')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                TextInput::make('discount_item')
                                    ->label('Discount Item')
                                    ->prefix('IDR')
                                    ->numeric()
                                    ->default(0)
                                    ->nullable()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $get, $state) {
                                        $price = $get('price') ?? 0;
                                        $quantity = $get('quantity') ?? 1;
                                        $discountItem = $state * $quantity;
                                        $subtotal = $price * $quantity - $discountItem;
                                        $set('subtotal', $subtotal);

                                        $items = $get('../../saleDetails') ?? [];
                                        $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_amount', $total);
                                        $discount = $get('../../discount_amount') ?? 0;
                                        $tax = $get('../../tax_amount') ?? 0;
                                        $grandTotal = $total - $discount + $tax;
                                        $set('../../grand_total', $grandTotal);
                                    }),
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->prefix('IDR')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->columnSpanFull()
                            ->required()
                            ->columns(4),
                    ])
                    ->columns(1),
                Section::make('Transaction Details')
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->prefix('IDR')
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->prefix('IDR')
                            ->default(0)
                            ->nullable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $get, $state) {
                                $total = $get('total_amount') ?? 0;
                                $tax = $get('tax_amount') ?? 0;
                                $grandTotal = $total - $state + $tax;
                                $set('grand_total', $grandTotal);
                            }),
                        TextInput::make('tax_amount')
                            ->label('Tax Amount')
                            ->prefix('IDR')
                            ->default(0)
                            ->nullable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $get, $state) {
                                $total = $get('total_amount') ?? 0;
                                $discount = $get('discount_amount') ?? 0;
                                $grandTotal = $total - $discount + $state;
                                $set('grand_total', $grandTotal);
                            }),
                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->prefix('IDR')
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                    ])
                    ->columns(4),
            ])
            ->columns(1);
    }
}
