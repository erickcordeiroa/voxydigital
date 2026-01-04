<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClients extends CreateRecord
{
    protected static string $resource = ClientsResource::class;

    protected function afterCreate(): void
    {
        $tenant = $this->record;

        $existsGateway = $tenant->paymentGateways()
            ->where('provider', 'mercadopago')
            ->exists();

        if (!$existsGateway) {
            $tenant->paymentGateways()->create([
                'provider' => 'mercadopago',
                'name' => 'Mercado Pago',
                'is_active' => false,
                'credentials' => [
                    'access_token' => '',
                    'public_key' => '',
                ],
            ]);
        }
    }
}
