<?php

namespace App\Filament\Resources\PaymentGateways\Pages;

use App\Filament\Resources\PaymentGateways\PaymentGatewaysResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentGateways extends CreateRecord
{
    protected static string $resource = PaymentGatewaysResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Organizar credenciais em JSON a partir dos campos separados
        $credentials = [];
        
        if (isset($data['credentials_access_token']) && $data['credentials_access_token']) {
            $credentials['access_token'] = $data['credentials_access_token'];
        }
        
        if (isset($data['credentials_public_key']) && $data['credentials_public_key']) {
            $credentials['public_key'] = $data['credentials_public_key'];
        }
        
        if (isset($data['credentials_api_key']) && $data['credentials_api_key']) {
            $credentials['api_key'] = $data['credentials_api_key'];
        }
        
        if (isset($data['credentials_api_secret']) && $data['credentials_api_secret']) {
            $credentials['api_secret'] = $data['credentials_api_secret'];
        }
        
        if (isset($data['credentials_base_url']) && $data['credentials_base_url']) {
            $credentials['base_url'] = $data['credentials_base_url'];
        }
        
        // Remover campos temporários
        unset($data['credentials_access_token']);
        unset($data['credentials_public_key']);
        unset($data['credentials_api_key']);
        unset($data['credentials_api_secret']);
        unset($data['credentials_base_url']);
        
        $data['credentials'] = $credentials;
        
        return $data;
    }
}

