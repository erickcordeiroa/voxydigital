<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Show the subscription settings page.
     */
    public function edit(Request $request): Response
    {
        $tenant = app('tenant');
        
        // Buscar assinatura ativa ou a mais recente
        $subscription = $tenant->subscriptions()
            ->with('paymentGateway')
            ->orderBy('created_at', 'desc')
            ->first();

        // Buscar payment gateways disponíveis
        $paymentGateways = $tenant->activePaymentGateways()
            ->get()
            ->map(fn ($gateway) => [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'provider' => $gateway->provider,
            ]);

        return Inertia::render('settings/Subscription', [
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'plan_name' => $subscription->plan_name,
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'status' => $subscription->status,
                'billing_cycle' => $subscription->billing_cycle,
                'starts_at' => $subscription->starts_at?->format('d/m/Y H:i'),
                'ends_at' => $subscription->ends_at?->format('d/m/Y H:i'),
                'next_billing_date' => $subscription->next_billing_date?->format('d/m/Y H:i'),
                'can_cancel' => $subscription->status === 'active',
            ] : null,
            'paymentGateways' => $paymentGateways,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Store a new subscription.
     */
    public function store(Request $request): RedirectResponse
    {
        $tenant = app('tenant');

        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'currency' => 'nullable|string|size:3|default:BRL',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:active,pending,cancelled,expired',
            'payment_gateway_id' => 'nullable|exists:payment_gateways,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'next_billing_date' => 'nullable|date',
        ]);

        // Se não houver payment_gateway_id, buscar o primeiro ativo
        if (empty($validated['payment_gateway_id'])) {
            $gateway = $tenant->activePaymentGateways()->first();
            if ($gateway) {
                $validated['payment_gateway_id'] = $gateway->id;
            }
        }

        // Calcular datas se não fornecidas
        $startsAt = $validated['starts_at'] ? \Carbon\Carbon::parse($validated['starts_at']) : now();
        $validated['starts_at'] = $startsAt;

        if (empty($validated['next_billing_date']) && $validated['status'] === 'active') {
            $billingCycle = $validated['billing_cycle'];
            $validated['next_billing_date'] = $billingCycle === 'monthly' 
                ? $startsAt->copy()->addMonth() 
                : $startsAt->copy()->addYear();
        }

        $subscription = \App\Models\Subscription::create([
            'tenant_id' => $tenant->id,
            'payment_gateway_id' => $validated['payment_gateway_id'] ?? null,
            'plan_name' => $validated['plan_name'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'BRL',
            'status' => $validated['status'],
            'billing_cycle' => $validated['billing_cycle'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'] ? \Carbon\Carbon::parse($validated['ends_at']) : null,
            'next_billing_date' => $validated['next_billing_date'] ? \Carbon\Carbon::parse($validated['next_billing_date']) : null,
        ]);

        return redirect()->route('subscription.edit')
            ->with('status', 'Assinatura criada com sucesso!');
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $tenant = app('tenant');
        
        $subscription = $tenant->subscriptions()
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscription) {
            return redirect()->route('subscription.edit')
                ->with('status', 'Nenhuma assinatura ativa encontrada.');
        }

        try {
            $this->subscriptionService->cancelSubscription($subscription);

            return redirect()->route('subscription.edit')
                ->with('status', 'Assinatura cancelada com sucesso. Você continuará com acesso até o final do período já pago.');
        } catch (\Exception $e) {
            return redirect()->route('subscription.edit')
                ->with('status', 'Erro ao cancelar assinatura: ' . $e->getMessage());
        }
    }

    /**
     * Reactivate a cancelled subscription.
     */
    public function reactivate(Request $request, $id): RedirectResponse
    {
        $tenant = app('tenant');
        
        $subscription = $tenant->subscriptions()->findOrFail($id);

        if ($subscription->status !== 'cancelled') {
            return redirect()->route('subscription.edit')
                ->with('status', 'Apenas assinaturas canceladas podem ser reativadas.');
        }

        // Calcular próxima data de cobrança baseada no ciclo
        $billingCycle = $subscription->billing_cycle;
        $startsAt = now();
        $nextBillingDate = $billingCycle === 'monthly' 
            ? $startsAt->copy()->addMonth() 
            : $startsAt->copy()->addYear();

        $subscription->update([
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => null,
            'next_billing_date' => $nextBillingDate,
        ]);

        return redirect()->route('subscription.edit')
            ->with('status', 'Assinatura reativada com sucesso!');
    }
}

