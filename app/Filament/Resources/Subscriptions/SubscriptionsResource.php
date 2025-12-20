<?php

namespace App\Filament\Resources\Subscriptions;

use App\Filament\Resources\Subscriptions\Pages\CreateSubscriptions;
use App\Filament\Resources\Subscriptions\Pages\EditSubscriptions;
use App\Filament\Resources\Subscriptions\Pages\ListSubscriptions;
use App\Filament\Resources\Subscriptions\Schemas\SubscriptionsForm;
use App\Filament\Resources\Subscriptions\Tables\SubscriptionsTable;
use App\Models\Subscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubscriptionsResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'Assinaturas';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptions::route('/'),
            'create' => CreateSubscriptions::route('/create'),
            'edit' => EditSubscriptions::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Assinaturas';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Assinaturas';
    }

    public static function getModelLabel(): string
    {
        return 'Assinatura';
    }
}

