<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

            // Configurar SDK do Mercado Pago
            $tenant = $order->tenant;
            
            if (!$tenant || !$tenant->mp_access_token) {
                Log::error('Tenant ou Access Token não encontrado', ['order_id' => $order->id]);
                return response()->json(['error' => 'Invalid tenant'], 400);
            }

            MercadoPagoConfig::setAccessToken($tenant->mp_access_token);

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
}
