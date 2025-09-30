<?php

namespace App\Filament\Resources\ReturnPurchases;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\ReturnPurchase;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ReturnPurchases\Pages\EditReturnPurchase;
use App\Filament\Resources\ReturnPurchases\Pages\ViewReturnPurchase;
use App\Filament\Resources\ReturnPurchases\Pages\ListReturnPurchases;
use App\Filament\Resources\ReturnPurchases\Pages\CreateReturnPurchase;
use App\Filament\Resources\ReturnPurchases\Schemas\ReturnPurchaseForm;
use App\Filament\Resources\ReturnPurchases\Tables\ReturnPurchasesTable;
use App\Filament\Resources\ReturnPurchases\Schemas\ReturnPurchaseInfolist;

class ReturnPurchaseResource extends Resource
{
    protected static ?string $model = ReturnPurchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ShoppingCart;

    protected static string|\UnitEnum|null $navigationGroup = 'Return Management';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'ReturnPurchase';

    public static function form(Schema $schema): Schema
    {
        return ReturnPurchaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReturnPurchaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReturnPurchasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReturnPurchases::route('/'),
            'create' => CreateReturnPurchase::route('/create'),
            'view' => ViewReturnPurchase::route('/{record}'),
            'edit' => EditReturnPurchase::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['sale.invoice_number'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Date' => $record->date,
            'Invoice Number' => $record->purchase->invoice_number,
            'Total Return' => 'IDR ' . number_format($record->total_return, 2),
            'Payment Method' => $record->paymentMethod?->name ?? 'N/A',
        ];
    }
}
