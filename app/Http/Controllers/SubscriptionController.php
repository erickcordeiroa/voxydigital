<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Create a new subscription
     */
    public function store(Request $request): JsonResponse
    {
        $tenant = app('tenant');
        
        $data = $request->validate([
            'plan_name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'currency' => 'nullable|string|size:3',
            'billing_cycle' => 'nullable|in:monthly,yearly',
            'customer_email' => 'nullable|email',
        ]);

        try {
            $subscription = $this->subscriptionService->createSubscription($tenant, $data);

            return response()->json([
                'success' => true,
                'message' => 'Assinatura criada com sucesso',
                'data' => $subscription,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancel(Request $request, $id): JsonResponse
    {
        $subscription = \App\Models\Subscription::findOrFail($id);
        
        // Verificar se a assinatura pertence ao tenant atual
        if ($subscription->tenant_id !== app('tenant_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado',
            ], 403);
        }

        try {
            $this->subscriptionService->cancelSubscription($subscription);

            return response()->json([
                'success' => true,
                'message' => 'Assinatura cancelada com sucesso',
                'data' => $subscription->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get current subscription
     */
    public function current(): JsonResponse
    {
        $tenant = app('tenant');
        $subscription = $tenant->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma assinatura ativa encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $subscription,
        ]);
    }
}
