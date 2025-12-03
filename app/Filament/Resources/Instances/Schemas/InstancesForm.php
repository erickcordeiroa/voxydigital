<?php

namespace App\Filament\Resources\Instances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class InstancesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->label('Cliente'),
                Group::make()->schema([
                    TextInput::make('instance')
                        ->required()
                        ->label('Instance'),
                    TextInput::make('api_key')
                        ->required()
                        ->label('API Key'),
                ])->columns(2)->columnSpanFull(),
                Group::make()->schema([
                    TextInput::make('url')
                        ->required()
                        ->label('URL'),
                    TextInput::make('phone')
                        ->required()
                        ->label('WhatsApp'),
                ])->columns(2)->columnSpanFull(),
                Toggle::make('status')
                    ->label('Ativo')
                    ->default(true)
                    ->columnSpanFull()
            ]);


    }
}
