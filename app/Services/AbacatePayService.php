<?php

namespace App\Services;

use App\Models\PaymentGateway;
use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbacatePayService
{
    private string $baseUrl;
    private string $apiKey;
    private string $apiSecret;

    public function __construct(PaymentGateway $gateway)
    {
        $this->baseUrl = $gateway->getCredential('base_url', 'https://api.abacatepay.com');
        $this->apiKey = $gateway->getCredential('api_key');
        $this->apiSecret = $gateway->getCredential('api_secret');

        if (!$this->apiKey || !$this->apiSecret) {
            throw new Exception('AbacatePay credentials not configured');
        }
    }

    /**
     * Create a subscription
     */
    public function createSubscription(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/subscriptions", [
                'plan_name' => $data['plan_name'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'BRL',
                'billing_cycle' => $data['billing_cycle'] ?? 'monthly',
                'customer' => $data['customer'],
                'metadata' => $data['metadata'] ?? [],
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AbacatePay subscription creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new Exception('Failed to create subscription: ' . $response->body());
        } catch (Exception $e) {
            Log::error('AbacatePay service error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/subscriptions/{$subscriptionId}/cancel");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AbacatePay subscription cancellation failed', [
                'subscription_id' => $subscriptionId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new Exception('Failed to cancel subscription: ' . $response->body());
        } catch (Exception $e) {
            Log::error('AbacatePay cancel error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get subscription details
     */
    public function getSubscription(string $subscriptionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
            ])->get("{$this->baseUrl}/api/v1/subscriptions/{$subscriptionId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AbacatePay get subscription failed', [
                'subscription_id' => $subscriptionId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new Exception('Failed to get subscription: ' . $response->body());
        } catch (Exception $e) {
            Log::error('AbacatePay get subscription error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->apiSecret);
        return hash_equals($expectedSignature, $signature);
    }
}

