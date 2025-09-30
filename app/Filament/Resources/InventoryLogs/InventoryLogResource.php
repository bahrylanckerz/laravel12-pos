<?php

namespace App\Filament\Resources\InventoryLogs;

use App\Filament\Resources\InventoryLogs\Pages\CreateInventoryLog;
use App\Filament\Resources\InventoryLogs\Pages\EditInventoryLog;
use App\Filament\Resources\InventoryLogs\Pages\ListInventoryLogs;
use App\Filament\Resources\InventoryLogs\Pages\ViewInventoryLog;
use App\Filament\Resources\InventoryLogs\Schemas\InventoryLogForm;
use App\Filament\Resources\InventoryLogs\Schemas\InventoryLogInfolist;
use App\Filament\Resources\InventoryLogs\Tables\InventoryLogsTable;
use App\Models\InventoryLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InventoryLogResource extends Resource
{
    protected static ?string $model = InventoryLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Square3Stack3d;

    protected static string|\UnitEnum|null $navigationGroup = 'Products Management';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'InventoryLog';

    public static function form(Schema $schema): Schema
    {
        return InventoryLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InventoryLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryLogsTable::configure($table);
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
            'index' => ListInventoryLogs::route('/'),
            'create' => CreateInventoryLog::route('/create'),
            'view' => ViewInventoryLog::route('/{record}'),
            'edit' => EditInventoryLog::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['product.name'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'User' => $record->user->name,
            'Type' => $record->change_type,
            'Quantity' => $record->quantity_change,
        ];
    }
}
