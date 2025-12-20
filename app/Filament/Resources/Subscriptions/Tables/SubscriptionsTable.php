<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->searchable()
                    ->sortable()
                    ->label('Cliente'),
                TextColumn::make('plan_name')
                    ->searchable()
                    ->sortable()
                    ->label('Plano'),
                TextColumn::make('amount')
                    ->label('Valor')
                    ->formatStateUsing(fn ($state, $record) => 
                        'R$ ' . number_format($state / 100, 2, ',', '.') . ' / ' . 
                        ($record->billing_cycle === 'monthly' ? 'mês' : 'ano')
                    )
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'active' => 'Ativa',
                        'cancelled' => 'Cancelada',
                        'expired' => 'Expirada',
                        'pending' => 'Pendente',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'active' => 'success',
                        'cancelled' => 'warning',
                        'expired' => 'danger',
                        'pending' => 'secondary',
                        default => 'gray',
                    }),
                TextColumn::make('billing_cycle')
                    ->label('Ciclo')
                    ->formatStateUsing(fn (string $state): string => 
                        $state === 'monthly' ? 'Mensal' : 'Anual'
                    ),
                TextColumn::make('starts_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Início'),
                TextColumn::make('ends_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Término'),
                TextColumn::make('next_billing_date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Próxima Cobrança'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Criado em'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Ativa',
                        'cancelled' => 'Cancelada',
                        'expired' => 'Expirada',
                        'pending' => 'Pendente',
                    ])
                    ->label('Status'),
                SelectFilter::make('billing_cycle')
                    ->options([
                        'monthly' => 'Mensal',
                        'yearly' => 'Anual',
                    ])
                    ->label('Ciclo de Cobrança'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            // Filtrar apenas assinaturas que não estão canceladas
                            $records->filter(fn ($record) => $record->status !== 'cancelled')
                                ->each(fn ($record) => $record->delete());
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}

