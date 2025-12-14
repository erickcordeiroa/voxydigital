<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class PaymentService
{
    public function processPayment(Order $order, string $paymentMethod, ?array $cardData = null): array
    {
        $tenant = app('tenant');
        
        if (!$tenant->mp_access_token) {
            throw new Exception('Mercado Pago Access Token não configurado para este tenant');
        }

        if ($paymentMethod === 'pix') {
            return $this->processPixPayment($order, $tenant);
        }

        throw new Exception('Método de pagamento inválido. Atualmente apenas PIX é suportado.');
    }

    private function processPixPayment(Order $order, $tenant): array
    {
        try {
            // Configurar SDK do Mercado Pago
            MercadoPagoConfig::setAccessToken($tenant->mp_access_token);

            // Criar cliente de pagamento
            $client = new PaymentClient();

            // Dados do pagamento PIX
            $paymentData = [
                "transaction_amount" => $order->total / 100, // Converter centavos para reais
                "description" => "Pedido #{$order->id} - {$tenant->name}",
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $order->customer_email,
                    "first_name" => $order->customer_name,
                ]
            ];

            Log::info('Criando pagamento PIX', [
                'order_id' => $order->id,
                'amount' => $order->total / 100
            ]);

            // Criar pagamento
            $payment = $client->create($paymentData);

            Log::info('Pagamento PIX criado', [
                'payment_id' => $payment->id,
                'status' => $payment->status
            ]);

            if ($payment->status === 'pending' && isset($payment->point_of_interaction)) {
                return [
                    'success' => true,
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'qr_code' => $payment->point_of_interaction->transaction_data->qr_code ?? null,
                    'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
                ];
            }

            throw new Exception('Erro ao gerar QR Code do PIX');
        } catch (MPApiException $e) {
            Log::error('Erro no pagamento PIX (MPApiException): ' . $e->getMessage());
            Log::error('API Response: ' . json_encode($e->getApiResponse()));
            throw new Exception('Erro ao processar pagamento PIX: ' . $e->getMessage());
        } catch (Exception $e) {
            Log::error('Erro no pagamento PIX: ' . $e->getMessage());
            throw new Exception('Erro ao processar pagamento PIX: ' . $e->getMessage());
        }
    }
}
