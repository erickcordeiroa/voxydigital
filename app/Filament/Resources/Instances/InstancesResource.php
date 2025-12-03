<?php

namespace App\Filament\Resources\Instances;

use App\Filament\Resources\Instances\Pages\CreateInstances;
use App\Filament\Resources\Instances\Pages\EditInstances;
use App\Filament\Resources\Instances\Pages\ListInstances;
use App\Filament\Resources\Instances\Schemas\InstancesForm;
use App\Filament\Resources\Instances\Tables\InstancesTable;
use App\Models\Instance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstancesResource extends Resource
{
    protected static ?string $model = Instance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Instance';

    public static function form(Schema $schema): Schema
    {
        return InstancesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstancesTable::configure($table);
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
            'index' => ListInstances::route('/'),
            'create' => CreateInstances::route('/create'),
            'edit' => EditInstances::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Instancias';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Instancias';
    }

    public static function getModelLabel(): string
    {
        return 'Instancia';
    }
}
