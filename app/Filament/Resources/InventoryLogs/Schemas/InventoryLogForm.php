<?php

namespace App\Filament\Resources\InventoryLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InventoryLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('product_id')
                    ->numeric(),
                Select::make('change_type')
                    ->options(['in' => 'In', 'out' => 'Out'])
                    ->required(),
                TextInput::make('quantity_change')
                    ->required()
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
