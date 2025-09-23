<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Method Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Description')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ])
            ->columns(1);
    }
}
