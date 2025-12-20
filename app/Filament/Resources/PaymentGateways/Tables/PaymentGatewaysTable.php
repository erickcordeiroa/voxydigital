<?php

namespace App\Filament\Resources\PaymentGateways\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PaymentGatewaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->searchable()
                    ->sortable()
                    ->label('Cliente'),
                TextColumn::make('provider')
                    ->searchable()
                    ->sortable()
                    ->label('Provedor')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'mercadopago' => 'Mercado Pago',
                        'abacatepay' => 'AbacatePay',
                        default => ucfirst($state),
                    }),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                ToggleColumn::make('is_active')
                    ->label('Ativo'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Criado em'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

