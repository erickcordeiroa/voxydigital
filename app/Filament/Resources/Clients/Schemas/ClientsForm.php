<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class ClientsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Razão Social')
                    ->required()
                    ->columnSpanFull(),

                Group::make()
                    ->schema([
                        TextInput::make('document')
                            ->label('CNPJ/CPF')
                            ->required(),

                        TextInput::make('domain')
                            ->label('Domínio')
                            ->unique()
                            ->required(),
                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->required(),
                    ])->columns(3)->columnSpanFull(),

                Group::make()
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->directory('tenants')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        FileUpload::make('cover')
                            ->label('Capa')
                            ->directory('tenants/cover')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                    ])->columns(2)->columnSpanFull(),

                Group::make()->schema([
                    ColorPicker::make('custom_button')
                        ->label('Cor do Botão Personalizado')
                        ->default('#FF0000'),
                    ColorPicker::make('custom_button_text')
                        ->label('Cor do Texto do Botão Personalizado')
                        ->default('#FFFFFF'),
                    ColorPicker::make('custom_title_color')
                        ->label('Cor do Título Personalizado')
                        ->default('#FFFFFF'),
                ])->columns(3)->columnSpanFull(),

                Group::make()
                    ->schema([
                        DatePicker::make('dt_expiration')
                            ->label('Data de Expiração')
                            ->default(now()->addMonth()->format('Y-m-d')),
                        TextInput::make('tax_fixed')
                            ->label('Taxa Fixa de Entrega')
                            ->prefix('R$')
                            ->numeric()
                    ])->columns(2)->columnSpanFull(),

                Toggle::make('status')
                    ->label('Ativo')
                    ->default(true)
                    ->extraAttributes([
                        'class' => 'w-full mt-auto',
                    ]),
            ]);
    }
}
