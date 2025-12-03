<?php

namespace App\Filament\Resources\Instances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class InstancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->searchable()
                    ->label('Cliente'),
                TextColumn::make('instance')
                    ->searchable()
                    ->label('Instance'),
                TextColumn::make('url')
                    ->searchable()
                    ->label('URL'),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('WhatsApp'),
                ToggleColumn::make('status')
                    ->label('Ativo'),
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
