<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Razão Social')
                    ->searchable(),
                TextColumn::make('domain')
                    ->label('Domínio')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),
                TextColumn::make('dt_expiration')
                    ->label('Data de Expiração')
                    ->date('d/m/Y')
                    ->searchable(),
                TextColumn::make('tax_fixed')
                    ->label('Taxa Fixa de Entrega')
                    ->formatStateUsing(fn($state) => 'R$ ' . number_format($state / 100, 2, ',', '.'))
                    ->searchable(),
                ToggleColumn::make('status')
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
