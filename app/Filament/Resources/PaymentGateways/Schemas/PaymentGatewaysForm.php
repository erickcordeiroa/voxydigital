<?php

namespace App\Filament\Resources\PaymentGateways\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class PaymentGatewaysForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required()
                    ->searchable()
                    ->label('Cliente')
                    ->columnSpanFull(),

                Select::make('provider')
                    ->options([
                        'mercadopago' => 'Mercado Pago',
                        'abacatepay' => 'AbacatePay',
                    ])
                    ->required()
                    ->label('Provedor')
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Nome')
                    ->placeholder('Ex: Mercado Pago Principal')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true)
                    ->columnSpanFull(),

                Group::make()
                    ->schema([
                        TextInput::make('credentials_access_token')
                            ->label('Access Token')
                            ->password()
                            ->visible(fn ($get) => $get('provider') === 'mercadopago')
                            ->columnSpanFull(),

                        TextInput::make('credentials_public_key')
                            ->label('Chave Pública')
                            ->visible(fn ($get) => $get('provider') === 'mercadopago')
                            ->columnSpanFull(),

                        TextInput::make('credentials_api_key')
                            ->label('API Key')
                            ->password()
                            ->visible(fn ($get) => $get('provider') === 'abacatepay')
                            ->columnSpanFull(),

                        TextInput::make('credentials_api_secret')
                            ->label('API Secret')
                            ->password()
                            ->visible(fn ($get) => $get('provider') === 'abacatepay')
                            ->columnSpanFull(),

                        TextInput::make('credentials_base_url')
                            ->label('URL Base')
                            ->placeholder('https://api.abacatepay.com')
                            ->visible(fn ($get) => $get('provider') === 'abacatepay')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

