<?php

namespace App\Filament\Resources\PaymentGateways;

use App\Filament\Resources\PaymentGateways\Pages\CreatePaymentGateways;
use App\Filament\Resources\PaymentGateways\Pages\EditPaymentGateways;
use App\Filament\Resources\PaymentGateways\Pages\ListPaymentGateways;
use App\Filament\Resources\PaymentGateways\Schemas\PaymentGatewaysForm;
use App\Filament\Resources\PaymentGateways\Tables\PaymentGatewaysTable;
use App\Models\PaymentGateway;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentGatewaysResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'Gateways de Pagamento';

    public static function form(Schema $schema): Schema
    {
        return PaymentGatewaysForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentGatewaysTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentGateways::route('/'),
            'create' => CreatePaymentGateways::route('/create'),
            'edit' => EditPaymentGateways::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Gateways de Pagamento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Gateways de Pagamento';
    }

    public static function getModelLabel(): string
    {
        return 'Gateway de Pagamento';
    }
}

