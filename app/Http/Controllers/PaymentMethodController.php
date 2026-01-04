<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');
        
        // Buscar todos os gateways disponíveis (pode ser expandido para incluir gateways padrão)
        $availableProviders = [
            [
                'provider' => 'mercadopago',
                'name' => 'Mercado Pago',
                'logo' => '/images/mercado-pago-logo.png',
            ],
        ];
        
        // Buscar gateways do tenant
        $gateways = $tenant->paymentGateways()->get()->map(function ($gateway) {
            return [
                'id' => $gateway->id,
                'provider' => $gateway->provider,
                'name' => $gateway->name ?? $this->getProviderName($gateway->provider),
                'is_active' => $gateway->is_active,
                'has_credentials' => !empty($gateway->credentials),
                'credentials' => $gateway->credentials, // Para pré-preencher o formulário
            ];
        });
        
        // Criar lista completa com gateways disponíveis e existentes
        $allGateways = collect($availableProviders)->map(function ($provider) use ($gateways) {
            $existing = $gateways->firstWhere('provider', $provider['provider']);
            
            return [
                'id' => $existing['id'] ?? null,
                'provider' => $provider['provider'],
                'name' => $provider['name'],
                'logo' => $provider['logo'],
                'is_active' => $existing['is_active'] ?? false,
                'has_credentials' => $existing['has_credentials'] ?? false,
                'credentials' => $existing['credentials'] ?? null,
            ];
        });
    
        return Inertia::render('settings/PaymentMethod', [
            'gateways' => $allGateways,
        ]);
    }
    
    public function toggle(Request $request, $id)
    {
        $tenant = app('tenant');
        
        $gateway = $tenant->paymentGateways()->findOrFail($id);
        
        // Se está ativando e não tem credenciais, retornar erro
        if (!$gateway->is_active && empty($gateway->credentials)) {
            return back()->withErrors([
                'message' => 'Configure as credenciais antes de ativar o gateway de pagamento.',
            ]);
        }
        
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();
        
        return back()->with('status', $gateway->is_active 
            ? 'Gateway de pagamento ativado com sucesso.' 
            : 'Gateway de pagamento desativado com sucesso.');
    }
    
    public function update(Request $request, $id)
    {
        $tenant = app('tenant');
        
        $gateway = $tenant->paymentGateways()->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'credentials' => 'required|array',
        ]);
        
        // Validar credenciais baseado no provider
        if ($gateway->provider === 'mercadopago') {
            $request->validate([
                'credentials.access_token' => 'required|string',
                'credentials.public_key' => 'nullable|string',
            ]);
        } elseif ($gateway->provider === 'abacatepay') {
            $request->validate([
                'credentials.api_key' => 'required|string',
                'credentials.api_secret' => 'required|string',
                'credentials.base_url' => 'nullable|string|url',
            ]);
        }
        
        $gateway->name = $validated['name'] ?? $gateway->name;
        $gateway->credentials = $validated['credentials'];
        $gateway->is_active = true; // Ativar automaticamente ao salvar credenciais
        $gateway->save();
        
        return back()->with('status', 'Credenciais salvas com sucesso.');
    }
    
    public function store(Request $request)
    {
        $tenant = app('tenant');
        
        $validated = $request->validate([
            'provider' => 'required|string|in:mercadopago,abacatepay',
            'name' => 'nullable|string|max:255',
            'credentials' => 'required|array',
        ]);
        
        // Validar credenciais baseado no provider
        if ($validated['provider'] === 'mercadopago') {
            $request->validate([
                'credentials.access_token' => 'required|string',
                'credentials.public_key' => 'nullable|string',
            ]);
        } elseif ($validated['provider'] === 'abacatepay') {
            $request->validate([
                'credentials.api_key' => 'required|string',
                'credentials.api_secret' => 'required|string',
                'credentials.base_url' => 'nullable|string|url',
            ]);
        }
        
        // Verificar se já existe gateway para este provider
        $existing = $tenant->paymentGateways()->where('provider', $validated['provider'])->first();
        
        if ($existing) {
            $existing->name = $validated['name'] ?? $existing->name;
            $existing->credentials = $validated['credentials'];
            $existing->is_active = true;
            $existing->save();
            
            return back()->with('status', 'Gateway de pagamento atualizado com sucesso.');
        }
        
        $gateway = $tenant->paymentGateways()->create([
            'provider' => $validated['provider'],
            'name' => $validated['name'] ?? $this->getProviderName($validated['provider']),
            'credentials' => $validated['credentials'],
            'is_active' => true,
        ]);
        
        return back()->with('status', 'Gateway de pagamento criado com sucesso.');
    }
    
    private function getProviderName(string $provider): string
    {
        return match($provider) {
            'mercadopago' => 'Mercado Pago',
            'abacatepay' => 'AbacatePay',
            default => ucfirst($provider),
        };
    }
}
