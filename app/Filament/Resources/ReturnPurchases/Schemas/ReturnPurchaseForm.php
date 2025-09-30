<?php

namespace App\Filament\Resources\ReturnPurchases\Schemas;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class ReturnPurchaseForm
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
                        Select::make('purchase_id')
                            ->label('Purchase Invoice Number')
                            ->relationship('purchase', 'invoice_number')
                            ->native(false)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $purchase = Purchase::find($state);

                                    if ($purchase) {
                                        $set('supplier_id', $purchase->supplier_id);
                                    }
                                } else {
                                    $set('supplier_id', null);
                                }
                            }),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'name')
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
                        Section::make('Return Purchase Details')
                            ->schema([
                                Repeater::make('returnPurchaseDetails')
                                    ->label('Items')
                                    ->relationship()
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->relationship('product', 'name', function ($query, callable $get) {
                                                $purchaseId = $get('../../purchase_id');

                                                if ($purchaseId) {
                                                    $query->whereIn('products.id', function ($sub) use ($purchaseId) {
                                                        $sub->select('product_id')
                                                            ->from('purchase_details')
                                                            ->where('purchase_id', $purchaseId);
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
                                                    $purchaseId = $get('../../purchase_id');

                                                    if ($purchaseId && $state) {
                                                        $detail = PurchaseDetail::where('purchase_id', $purchaseId)
                                                            ->where('product_id', $state)
                                                            ->first();

                                                        if ($detail) {
                                                            $set('price', $detail->price);
                                                            $set('subtotal', $detail->price * ($get('quantity') ?? 1));
                                                        }
                                                    }

                                                    $items = $get('../../returnPurchaseDetails') ?? [];
                                                    $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                                    $set('../../total_return', $total);
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
                                                $purchaseId = $get('../../purchase_id');
                                                $productId = $get('product_id');

                                                if ($purchaseId && $productId) {
                                                    $detail = PurchaseDetail::where('purchase_id', $purchaseId)
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

                                                $items = $get('../../returnPurchaseDetails') ?? [];
                                                $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                                $set('../../total_return', $total);
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
                                TextInput::make('total_return')
                                    ->label('Total Return')
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
