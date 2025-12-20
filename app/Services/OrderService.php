<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItems;
use App\Services\WhatsappService;
use App\Services\PaymentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function getPaginatedOrders(array $filters)
    {
        $tenantId = app('tenant_id');
        
        // Usa índice composto (tenant_id, status, created_at) para melhor performance
        $query = Order::where('tenant_id', $tenantId)
            ->with(['items.product', 'items.variation'])
            ->latest();

        $search = Arr::get($filters, 'search');
        if ($search) {
            // Otimiza busca usando índices - busca por ID primeiro (mais rápido)
            // Depois busca por nome e telefone (usando índices quando disponíveis)
            $query->where(function ($q) use ($search, $tenantId) {
                // Se for numérico, pode ser ID do pedido
                if (is_numeric($search)) {
                    $q->where('id', $search)
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                } else {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                }
            });
        }

        return [
            'orders' => $query->paginate(10),
            'filters' => ['search' => $search]
        ];
    }

    public function createOrderWithNotification(array $data): Order
    {
        // Criar pedido
        $order = Order::create([
            'tenant_id' => app('tenant_id'),
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'delivery_address' => $data['delivery_address'],
            'note' => $data['note'] ?? null,
            'total' => $data['total'],
            'status' => 'pending',
            'tax_fixed' => $data['tax_fixed'] ?? 0,
            'payment_method' => $data['payment_method'],
        ]);

        // Criar itens do pedido
        foreach ($data['items'] as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'variation_id' => $item['variation_id'] ?? null,
            ]);
        }

        $order->load(['items.product', 'items.variation']);

        // Verificar se o gateway de pagamento está ativo antes de processar
        $tenant = app('tenant');
        $gateway = $this->getPaymentGatewayForMethod($tenant, $data['payment_method']);

        if ($gateway) {
            // Gateway ativo - processar pagamento
            try {
                $paymentService = new PaymentService();
                $paymentResult = $paymentService->processPayment(
                    $order,
                    $data['payment_method'],
                    $data['card_data'] ?? null
                );

                // Atualizar pedido com dados do pagamento
                $order->update([
                    'payment_id' => $paymentResult['payment_id'],
                    'payment_status' => $paymentResult['status'],
                    'qr_code' => $paymentResult['qr_code'] ?? null,
                    'qr_code_base64' => $paymentResult['qr_code_base64'] ?? null,
                ]);

                // Se pagamento aprovado, atualizar status do pedido
                if ($paymentResult['success'] && $data['payment_method'] === 'credit_card') {
                    $order->update(['status' => 'approved']);
                }

            } catch (\Exception $e) {
                \Log::error('Erro ao processar pagamento: ' . $e->getMessage());
                $order->update([
                    'payment_status' => 'failed',
                    'note' => ($data['note'] ?? '') . "\n\nErro no pagamento: " . $e->getMessage()
                ]);
                throw $e;
            }
        } else {
            // Gateway não ativo ou não configurado - criar pedido sem processar pagamento
            \Log::info('Gateway de pagamento não ativo ou não configurado, pedido criado sem processar pagamento', [
                'order_id' => $order->id,
                'payment_method' => $data['payment_method'],
                'tenant_id' => $tenant->id,
            ]);

            $order->update([
                'payment_status' => 'pending',
                'note' => ($data['note'] ?? '') . "\n\nPagamento não processado: Gateway de pagamento não está ativo ou configurado."
            ]);
        }

        $tenant = app('tenant');
        // Comentado por questões de não utilização do whatsapp no momento.
        // if ($tenant && $tenant->whatsapp) {
        //     $msg = $this->buildOrderNotificationMessage($tenant, $data, $order);
        //     WhatsappService::send("+55{$tenant->whatsapp}", $msg);
        //     WhatsappService::sendToClient("+55{$data['customer_phone']}");
        // }
        
        return $order;
    }

    public function updateOrderStatusWithNotification(array $data): void
    {
        $order = Order::find($data['id']);
        $order->update(['status' => $data['status']]);
        $statusLabel = $this->getStatusLabel($data['status']);

        $msg = "*Pedido atualizado!*\n";
        $msg .= "O seu pedido foi atualizado para *{$statusLabel}*.\n";
        $msg .= "Agradecemos pela preferência!";

        //WhatsappService::send("+55{$order->customer_phone}", $msg);
    }

    private function buildOrderNotificationMessage($tenant, array $data, $order): string
    {
        $msg = "*Novo pedido recebido!*\n";
        $msg .= "*Cliente:* {$data['customer_name']}\n";
        $msg .= "*Telefone:* {$data['customer_phone']}\n";
        $msg .= "*Email:* {$data['customer_email']}\n";
        $msg .= "*Endereço:* {$data['delivery_address']}\n";
        $msg .= "*Observação:* {$data['note']}\n";
        $msg .= "*Itens:*\n";
        foreach ($order->items as $item) {
            $unitPrice = number_format($item->price / 100, 2, ',', '.');
            $subtotal = number_format(($item->price * $item->quantity) / 100, 2, ',', '.');
            $size = $item->variation ? "Tamanho: {$item->variation->size}" : '';
            $msg .= "- {$item->quantity}x {$item->product->name} | {$size} | Unitário: R$ {$unitPrice} | Subtotal: R$ {$subtotal}\n";
        }
        $msg .= "*Taxa Entrega:* R$ " . number_format($tenant->tax_fixed / 100, 2, ',', '.') . "\n";
        $msg .= "*Total:* R$ " . number_format($data['total'] / 100, 2, ',', '.');
        return $msg;
    }

    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendente',
            'preparing' => 'Preparando',
            'delivering' => 'Entregando',
            'delivered' => 'Entregue',
            'canceled' => 'Cancelado',
            default => ucfirst($status),
        };
    }

    /**
     * Get payment gateway for the given payment method
     */
    private function getPaymentGatewayForMethod($tenant, string $paymentMethod): ?\App\Models\PaymentGateway
    {
        // Mapear método de pagamento para provider
        $providerMap = [
            'pix' => 'mercadopago',
            'credit_card' => 'mercadopago',
            // Adicionar outros métodos conforme necessário
        ];

        $provider = $providerMap[$paymentMethod] ?? null;

        if (!$provider) {
            return null;
        }

        return $tenant->getPaymentGateway($provider);
    }
}