<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Create a new subscription
     */
    public function createSubscription(Tenant $tenant, array $data): Subscription
    {
        // Buscar gateway AbacatePay
        $gateway = $tenant->getPaymentGateway('abacatepay');
        
        if (!$gateway) {
            throw new Exception('AbacatePay gateway not configured for this tenant');
        }

        $abacatePayService = new AbacatePayService($gateway);

        // Preparar dados para AbacatePay
        $abacatePayData = [
            'plan_name' => $data['plan_name'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'BRL',
            'billing_cycle' => $data['billing_cycle'] ?? 'monthly',
            'customer' => [
                'name' => $tenant->name,
                'email' => $data['customer_email'] ?? null,
                'document' => $tenant->document,
            ],
            'metadata' => [
                'tenant_id' => $tenant->id,
                'tenant_domain' => $tenant->domain,
            ],
        ];

        // Criar assinatura no AbacatePay
        $abacatePayResponse = $abacatePayService->createSubscription($abacatePayData);

        // Calcular datas
        $startsAt = now();
        $billingCycle = $data['billing_cycle'] ?? 'monthly';
        $nextBillingDate = $billingCycle === 'monthly' 
            ? $startsAt->copy()->addMonth() 
            : $startsAt->copy()->addYear();

        // Criar assinatura no banco
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'payment_gateway_id' => $gateway->id,
            'plan_name' => $data['plan_name'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'BRL',
            'status' => 'active',
            'billing_cycle' => $billingCycle,
            'starts_at' => $startsAt,
            'next_billing_date' => $nextBillingDate,
            'abacatepay_subscription_id' => $abacatePayResponse['id'] ?? $abacatePayResponse['subscription_id'] ?? null,
            'metadata' => $abacatePayResponse,
        ]);

        Log::info('Subscription created', [
            'subscription_id' => $subscription->id,
            'tenant_id' => $tenant->id,
            'abacatepay_id' => $subscription->abacatepay_subscription_id,
        ]);

        return $subscription;
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(Subscription $subscription): Subscription
    {
        if ($subscription->status === 'cancelled') {
            throw new Exception('Subscription already cancelled');
        }

        $gateway = $subscription->paymentGateway;
        
        if (!$gateway || $gateway->provider !== 'abacatepay') {
            throw new Exception('Invalid payment gateway for subscription');
        }

        $abacatePayService = new AbacatePayService($gateway);

        // Cancelar no AbacatePay
        if ($subscription->abacatepay_subscription_id) {
            try {
                $abacatePayService->cancelSubscription($subscription->abacatepay_subscription_id);
            } catch (Exception $e) {
                Log::warning('Failed to cancel subscription in AbacatePay', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                // Continuar mesmo se falhar no gateway
            }
        }

        // Calcular data de término baseada no período já pago
        // O usuário mantém acesso até o final do período atual (até next_billing_date)
        $endsAt = $subscription->next_billing_date;
        
        // Se não houver next_billing_date, calcular baseado no ciclo de cobrança
        if (!$endsAt) {
            $billingCycle = $subscription->billing_cycle;
            $startsAt = $subscription->starts_at ?? now();
            $endsAt = $billingCycle === 'monthly' 
                ? $startsAt->copy()->addMonth() 
                : $startsAt->copy()->addYear();
        }

        // Atualizar no banco - mantém acesso até o final do período pago
        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => $endsAt,
        ]);

        Log::info('Subscription cancelled', [
            'subscription_id' => $subscription->id,
        ]);

        return $subscription;
    }

    /**
     * Process webhook from AbacatePay
     */
    public function processWebhook(array $webhookData): void
    {
        $subscriptionId = $webhookData['subscription_id'] ?? $webhookData['id'] ?? null;
        
        if (!$subscriptionId) {
            throw new Exception('Subscription ID not found in webhook data');
        }

        $subscription = Subscription::where('abacatepay_subscription_id', $subscriptionId)->first();

        if (!$subscription) {
            Log::warning('Subscription not found for webhook', [
                'abacatepay_subscription_id' => $subscriptionId,
            ]);
            return;
        }

        $event = $webhookData['event'] ?? $webhookData['type'] ?? null;

        switch ($event) {
            case 'subscription.paid':
            case 'payment.succeeded':
                $this->handlePaymentSuccess($subscription, $webhookData);
                break;
            
            case 'subscription.cancelled':
            case 'subscription.canceled':
                $this->handleCancellation($subscription, $webhookData);
                break;
            
            case 'subscription.expired':
                $this->handleExpiration($subscription, $webhookData);
                break;
            
            default:
                Log::info('Unhandled webhook event', [
                    'event' => $event,
                    'subscription_id' => $subscription->id,
                ]);
        }
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSuccess(Subscription $subscription, array $data): void
    {
        $billingCycle = $subscription->billing_cycle;
        $nextBillingDate = $billingCycle === 'monthly' 
            ? now()->addMonth() 
            : now()->addYear();

        $subscription->update([
            'status' => 'active',
            'next_billing_date' => $nextBillingDate,
            'metadata' => array_merge($subscription->metadata ?? [], [
                'last_payment' => $data,
                'last_payment_at' => now()->toIso8601String(),
            ]),
        ]);

        Log::info('Subscription payment processed', [
            'subscription_id' => $subscription->id,
            'next_billing_date' => $nextBillingDate,
        ]);
    }

    /**
     * Handle subscription cancellation
     */
    private function handleCancellation(Subscription $subscription, array $data): void
    {
        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => now(),
        ]);

        Log::info('Subscription cancelled via webhook', [
            'subscription_id' => $subscription->id,
        ]);
    }

    /**
     * Handle subscription expiration
     */
    private function handleExpiration(Subscription $subscription, array $data): void
    {
        $subscription->update([
            'status' => 'expired',
            'ends_at' => now(),
        ]);

        Log::info('Subscription expired via webhook', [
            'subscription_id' => $subscription->id,
        ]);
    }
}

