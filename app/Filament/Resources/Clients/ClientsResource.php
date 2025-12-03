<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClients;
use App\Filament\Resources\Clients\Pages\EditClients;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Filament\Resources\Clients\Pages\ViewClients;
use App\Filament\Resources\Clients\Schemas\ClientsForm;
use App\Filament\Resources\Clients\Schemas\ClientsInfolist;
use App\Filament\Resources\Clients\Tables\ClientsTable;
use App\Models\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClientsResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Clientes';

    public static function form(Schema $schema): Schema
    {
        return ClientsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClientsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClients::route('/create'),
            'view' => ViewClients::route('/{record}'),
            'edit' => EditClients::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Clientes';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Clientes';
    }

    public static function getModelLabel(): string
    {
        return 'Cliente';
    }
}
