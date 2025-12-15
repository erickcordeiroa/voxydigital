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
            // Validar access token
            $accessToken = trim($tenant->mp_access_token);
            
            Log::info('Configurando Mercado Pago', [
                'tenant_id' => $tenant->id,
                'token_prefix' => substr($accessToken, 0, 15) . '...',
                'token_length' => strlen($accessToken),
            ]);

            // Configurar SDK do Mercado Pago
            MercadoPagoConfig::setAccessToken($accessToken);

            // Criar cliente de pagamento
            $client = new PaymentClient();

            // Dados do pagamento PIX
            $paymentData = [
                "transaction_amount" => (float) ($order->total / 100), // Converter centavos para reais
                "description" => "Pedido #{$order->id} - {$tenant->name}",
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $order->customer_email,
                    "first_name" => $order->customer_name,
                ]
            ];

            Log::info('Criando pagamento PIX', [
                'order_id' => $order->id,
                'amount' => $order->total / 100,
                'email' => $order->customer_email,
                'payload' => $paymentData
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
            Log::error('Status Code: ' . ($e->getStatusCode() ?? 'N/A'));
            
            // Tentar extrair detalhes do erro do objeto MPResponse
            $errorMessage = $e->getMessage();
            try {
                $apiResponse = $e->getApiResponse();
                
                // MPResponse é um objeto, não array
                if ($apiResponse) {
                    $responseContent = $apiResponse->getContent();
                    Log::error('API Response Content: ' . json_encode($responseContent, JSON_PRETTY_PRINT));
                    
                    // Extrair mensagem de erro
                    if (isset($responseContent['message'])) {
                        $errorMessage = $responseContent['message'];
                    } elseif (isset($responseContent['cause'])) {
                        $causes = is_array($responseContent['cause']) ? $responseContent['cause'] : [$responseContent['cause']];
                        $errorMessage = implode(', ', array_map(function($cause) {
                            return isset($cause['description']) ? $cause['description'] : json_encode($cause);
                        }, $causes));
                    } elseif (isset($responseContent['error'])) {
                        $errorMessage = $responseContent['error'];
                    }
                }
            } catch (\Exception $ex) {
                Log::error('Erro ao processar resposta da API: ' . $ex->getMessage());
            }
            
            Log::error('Mensagem de erro final: ' . $errorMessage);
            throw new Exception('Erro ao processar pagamento PIX: ' . $errorMessage);
        } catch (Exception $e) {
            Log::error('Erro no pagamento PIX: ' . $e->getMessage());
            throw new Exception('Erro ao processar pagamento PIX: ' . $e->getMessage());
        }
    }
}
