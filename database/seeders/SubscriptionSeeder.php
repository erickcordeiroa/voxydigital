<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar ou criar um tenant
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Cliente de Teste',
                'document' => '12345678000190',
                'domain' => 'teste',
                'whatsapp' => '13999999999',
                'status' => true,
            ]);
        }

        // Buscar ou criar um payment gateway
        $paymentGateway = PaymentGateway::where('tenant_id', $tenant->id)
            ->where('provider', 'abacatepay')
            ->first();

        if (!$paymentGateway) {
            $paymentGateway = PaymentGateway::create([
                'tenant_id' => $tenant->id,
                'provider' => 'abacatepay',
                'name' => 'AbacatePay Principal',
                'is_active' => true,
                'credentials' => [
                    'api_key' => 'test_api_key',
                    'api_secret' => 'test_api_secret',
                    'base_url' => 'https://api.abacatepay.com',
                ],
            ]);
        }

        // Criar assinatura ativa
        $startsAt = Carbon::now()->subMonth(); // Começou há 1 mês
        $nextBillingDate = Carbon::now()->addMonth(); // Próxima cobrança em 1 mês

        Subscription::create([
            'tenant_id' => $tenant->id,
            'payment_gateway_id' => $paymentGateway->id,
            'plan_name' => 'Plano Premium Mensal',
            'amount' => 9900, // R$ 99,00 em centavos
            'currency' => 'BRL',
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => $startsAt,
            'ends_at' => null, // Sem data de término (ativa)
            'next_billing_date' => $nextBillingDate,
            'abacatepay_subscription_id' => 'sub_test_' . uniqid(),
            'metadata' => [
                'created_via' => 'seeder',
                'test' => true,
            ],
        ]);

        // Criar uma assinatura cancelada para testar o layout
        $cancelledStartsAt = Carbon::now()->subMonths(2);
        $cancelledEndsAt = Carbon::now()->addDays(15); // Acesso até 15 dias a partir de agora

        Subscription::create([
            'tenant_id' => $tenant->id,
            'payment_gateway_id' => $paymentGateway->id,
            'plan_name' => 'Plano Básico Mensal',
            'amount' => 4900, // R$ 49,00 em centavos
            'currency' => 'BRL',
            'status' => 'cancelled',
            'billing_cycle' => 'monthly',
            'starts_at' => $cancelledStartsAt,
            'ends_at' => $cancelledEndsAt, // Mantém acesso até esta data
            'next_billing_date' => null, // Não há próxima cobrança
            'abacatepay_subscription_id' => 'sub_cancelled_' . uniqid(),
            'metadata' => [
                'cancelled_at' => Carbon::now()->toIso8601String(),
                'created_via' => 'seeder',
                'test' => true,
            ],
        ]);

        $this->command->info('Assinaturas criadas com sucesso!');
        $this->command->info("Tenant: {$tenant->name} (ID: {$tenant->id})");
        $this->command->info("Assinatura ativa: Plano Premium Mensal - R$ 99,00");
        $this->command->info("Assinatura cancelada: Plano Básico Mensal - R$ 49,00");
    }
}

