<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\SubscriptionService;
use App\Services\AbacatePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class WebhookController extends Controller
{
    public function mercadoPago(Request $request)
    {
        Log::info('Webhook MercadoPago recebido', [
            'body' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            // Verificar se é uma notificação de pagamento
            $type = $request->input('type');
            
            if ($type !== 'payment') {
                Log::info('Webhook ignorado: tipo não é payment', ['type' => $type]);
                return response()->json(['status' => 'ignored'], 200);
            }

            // Obter ID do pagamento
            $paymentId = $request->input('data.id');
            
            if (!$paymentId) {
                Log::warning('Webhook sem payment ID');
                return response()->json(['error' => 'No payment ID'], 400);
            }

            Log::info('Processando pagamento', ['payment_id' => $paymentId]);

            // Buscar o pedido pelo payment_id
            $order = Order::where('payment_id', $paymentId)->first();

            if (!$order) {
                Log::warning('Pedido não encontrado para payment_id', ['payment_id' => $paymentId]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Buscar gateway ativo do Mercado Pago
            $tenant = $order->tenant;
            
            if (!$tenant) {
                Log::error('Tenant não encontrado', ['order_id' => $order->id]);
                return response()->json(['error' => 'Invalid tenant'], 400);
            }

            $gateway = $tenant->getPaymentGateway('mercadopago');
            
            if (!$gateway) {
                Log::error('Gateway Mercado Pago não encontrado', ['order_id' => $order->id, 'tenant_id' => $tenant->id]);
                return response()->json(['error' => 'Payment gateway not configured'], 400);
            }

            $accessToken = $gateway->getCredential('access_token');
            
            if (!$accessToken) {
                Log::error('Access Token não encontrado no gateway', ['order_id' => $order->id, 'gateway_id' => $gateway->id]);
                return response()->json(['error' => 'Access token not configured'], 400);
            }

            MercadoPagoConfig::setAccessToken($accessToken);

            // Buscar informações do pagamento no Mercado Pago
            $client = new PaymentClient();
            $payment = $client->get($paymentId);

            Log::info('Dados do pagamento obtidos', [
                'payment_id' => $paymentId,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail
            ]);

            // Atualizar status do pedido
            $order->update([
                'payment_status' => $payment->status,
            ]);

            // Se pagamento aprovado, atualizar status do pedido
            if ($payment->status === 'approved') {
                $order->update([
                    'status' => 'preparing', // Mudar de pending para preparing
                ]);
                
                Log::info('Pagamento aprovado, pedido atualizado', [
                    'order_id' => $order->id,
                    'payment_id' => $paymentId
                ]);
            }

            return response()->json(['status' => 'processed'], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook MercadoPago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    public function abacatePay(Request $request)
    {
        Log::info('Webhook AbacatePay recebido', [
            'body' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            // Verificar assinatura do webhook (se configurado)
            $signature = $request->header('X-AbacatePay-Signature');
            $payload = $request->getContent();

            // Buscar gateway AbacatePay para verificar assinatura
            // Por enquanto, vamos processar sem verificação de assinatura
            // mas isso deve ser implementado em produção
            
            $webhookData = $request->all();

            // Processar webhook através do SubscriptionService
            $subscriptionService = new SubscriptionService();
            $subscriptionService->processWebhook($webhookData);

            Log::info('Webhook AbacatePay processado com sucesso', [
                'event' => $webhookData['event'] ?? $webhookData['type'] ?? 'unknown',
            ]);

            return response()->json(['status' => 'processed'], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook AbacatePay', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Internal error'], 500);
        }
    }
}
