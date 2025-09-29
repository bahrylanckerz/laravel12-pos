<?php

namespace App\Filament\Resources\Purchases;

use BackedEnum;
use App\Models\Purchase;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Purchases\Pages\EditPurchase;
use App\Filament\Resources\Purchases\Pages\ViewPurchase;
use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Filament\Resources\Purchases\Pages\CreatePurchase;
use App\Filament\Resources\Purchases\Schemas\PurchaseForm;
use App\Filament\Resources\Purchases\Tables\PurchasesTable;
use App\Filament\Resources\Purchases\Schemas\PurchaseInfolist;
use App\Filament\Resources\Purchases\RelationManagers\PurchaseDetailsRelationManager;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ShoppingCart;

    protected static string|\UnitEnum|null $navigationGroup = 'Transaction Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Purchase';

    public static function form(Schema $schema): Schema
    {
        return PurchaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PurchaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PurchaseDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchases::route('/'),
            'create' => CreatePurchase::route('/create'),
            'view' => ViewPurchase::route('/{record}'),
            'edit' => EditPurchase::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['invoice_number'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Date' => $record->date,
            'Invoice Number' => $record->invoice_number,
            'Grand Total' => 'IDR ' . number_format($record->grand_total, 2),
        ];
    }
}
