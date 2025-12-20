<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class SubscriptionsForm
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

                Select::make('payment_gateway_id')
                    ->relationship('paymentGateway', 'name')
                    ->searchable()
                    ->label('Gateway de Pagamento')
                    ->columnSpanFull(),

                TextInput::make('plan_name')
                    ->label('Nome do Plano')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('amount')
                    ->label('Valor (em centavos)')
                    ->numeric()
                    ->required()
                    ->columnSpan(1),

                TextInput::make('currency')
                    ->label('Moeda')
                    ->default('BRL')
                    ->maxLength(3)
                    ->columnSpan(1),

                Select::make('status')
                    ->options([
                        'active' => 'Ativa',
                        'cancelled' => 'Cancelada',
                        'expired' => 'Expirada',
                        'pending' => 'Pendente',
                    ])
                    ->required()
                    ->label('Status')
                    ->columnSpan(1),

                Select::make('billing_cycle')
                    ->options([
                        'monthly' => 'Mensal',
                        'yearly' => 'Anual',
                    ])
                    ->required()
                    ->label('Ciclo de Cobrança')
                    ->columnSpan(1),

                DateTimePicker::make('starts_at')
                    ->label('Data de Início')
                    ->columnSpan(1),

                DateTimePicker::make('ends_at')
                    ->label('Data de Término')
                    ->columnSpan(1),

                DateTimePicker::make('next_billing_date')
                    ->label('Próxima Data de Cobrança')
                    ->columnSpan(1),

                TextInput::make('abacatepay_subscription_id')
                    ->label('ID da Assinatura (AbacatePay)')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}

