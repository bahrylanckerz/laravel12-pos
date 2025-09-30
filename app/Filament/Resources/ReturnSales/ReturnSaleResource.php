<?php

namespace App\Filament\Resources\ReturnSales;

use BackedEnum;
use App\Models\ReturnSale;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ReturnSales\Pages\EditReturnSale;
use App\Filament\Resources\ReturnSales\Pages\ViewReturnSale;
use App\Filament\Resources\ReturnSales\Pages\ListReturnSales;
use App\Filament\Resources\ReturnSales\Pages\CreateReturnSale;
use App\Filament\Resources\ReturnSales\RelationManagers\ReturnSaleDetailsRelationManager;
use App\Filament\Resources\ReturnSales\Schemas\ReturnSaleForm;
use App\Filament\Resources\ReturnSales\Tables\ReturnSalesTable;
use App\Filament\Resources\ReturnSales\Schemas\ReturnSaleInfolist;

class ReturnSaleResource extends Resource
{
    protected static ?string $model = ReturnSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ShoppingBag;

    protected static string|\UnitEnum|null $navigationGroup = 'Return Management';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'ReturnSale';

    public static function form(Schema $schema): Schema
    {
        return ReturnSaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReturnSaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReturnSalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ReturnSaleDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReturnSales::route('/'),
            'create' => CreateReturnSale::route('/create'),
            'view' => ViewReturnSale::route('/{record}'),
            'edit' => EditReturnSale::route('/{record}/edit'),
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
            'Invoice Number' => $record->sale->invoice_number,
            'Total Refund' => 'IDR ' . number_format($record->total_refund, 2),
            'Payment Method' => $record->paymentMethod?->name ?? 'N/A',
        ];
    }
}
