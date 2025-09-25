<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Product;
use App\Models\Customer;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;

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
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Customer Information')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $customer = Customer::find($state);
                                $set('email', $customer?->email);
                                $set('phone', $customer?->phone);
                                $set('address', $customer?->address);
                            })
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
                                return Customer::create($data)->getKey();
                            })
                            ->reactive(),
                        Placeholder::make('email')
                            ->label('Email Address')
                            ->content(fn(Get $get) => $get('customer_id') ? Customer::find($get('customer_id'))?->name : '-'),
                        Placeholder::make('phone')
                            ->label('Phone Number')
                            ->content(fn(Get $get) => $get('customer_id') ? Customer::find($get('customer_id'))?->phone : '-'),
                        Placeholder::make('address')
                            ->label('Address')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => $get('customer_id') ? Customer::find($get('customer_id'))?->address : '-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Group::make()
                    ->schema([
                        Section::make('Transaction Details')
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
                                                $taxPercentage = $get('../../tax_percentage') ?? 0;
                                                $totalAmount = $total - $discount;
                                                $taxAmount = ($taxPercentage / 100) * $totalAmount;
                                                $set('../../tax_amount', $taxAmount);
                                                $grandTotal = $totalAmount + $taxAmount;
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
                                                $taxPercentage = $get('../../tax_percentage') ?? 0;
                                                $totalAmount = $total - $discount;
                                                $taxAmount = ($taxPercentage / 100) * $totalAmount;
                                                $set('../../tax_amount', $taxAmount);
                                                $grandTotal = $totalAmount + $taxAmount;
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
                                    ->columns(2),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('Payment Information')
                            ->schema([
                                Select::make('payment_method_id')
                                    ->label('Payment Method')
                                    ->relationship('paymentMethod', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required(),
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
                                        $taxPercentage = $get('tax_percentage') ?? 0;
                                        $totalAmount = $total - $state;
                                        $taxAmount = ($taxPercentage / 100) * $totalAmount;
                                        $set('tax_amount', $taxAmount);
                                        $grandTotal = $totalAmount + $taxAmount;
                                        $set('grand_total', $grandTotal);
                                    }),
                                TextInput::make('tax_percentage')
                                    ->label('Tax Percentage')
                                    ->suffix('%')
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->numeric()
                                    ->nullable()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $get, $state) {
                                        $total = $get('total_amount') ?? 0;
                                        $discount = $get('discount_amount') ?? 0;
                                        $totalAmount = $total - $discount;
                                        $taxAmount = ($state / 100) * $totalAmount;
                                        $set('tax_amount', $taxAmount);
                                        $grandTotal = $totalAmount + $taxAmount;
                                        $set('grand_total', $grandTotal);
                                    }),
                                TextInput::make('tax_amount')
                                    ->label('Tax Amount')
                                    ->prefix('IDR')
                                    ->default(0)
                                    ->nullable()
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('grand_total')
                                    ->label('Grand Total')
                                    ->prefix('IDR')
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(3);
    }
}
