<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Information')
                    ->description('Manage supplier details and contact information.')
                    ->schema([
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
                    ])
                    ->columns(2),
            ])
            ->columns(1);
    }
}
