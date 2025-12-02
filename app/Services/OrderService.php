<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItems;
use App\Services\WhatsappService;
use Illuminate\Support\Arr;

class OrderService
{
    public function getPaginatedOrders(array $filters)
    {
        $query = Order::with(['items.product', 'items.variation'])->latest();

        $search = Arr::get($filters, 'search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        return [
            'orders' => $query->paginate(10),
            'filters' => ['search' => $search]
        ];
    }

    public function createOrderWithNotification(array $data): void
    {
        $order = Order::create([
            'tenant_id' => app('tenant_id'),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'delivery_address' => $data['delivery_address'],
            'note' => $data['note'],
            'total' => $data['total'],
            'status' => 'pending',
            'tax_fixed' => $data['tax_fixed'],
        ]);

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

        $tenant = app('tenant');
        // Comentado por questões de não utilização do whatsapp no momento.
        // if ($tenant && $tenant->whatsapp) {
        //     $msg = $this->buildOrderNotificationMessage($tenant, $data, $order);
        //     WhatsappService::send("+55{$tenant->whatsapp}", $msg);
        //     WhatsappService::sendToClient("+55{$data['customer_phone']}");
        // }
    }

    public function updateOrderStatusWithNotification(array $data): void
    {
        $order = Order::find($data['id']);
        $order->update(['status' => $data['status']]);
        $statusLabel = $this->getStatusLabel($data['status']);

        $msg = "*Pedido atualizado!*\n";
        $msg .= "O seu pedido foi atualizado para *{$statusLabel}*.\n";
        $msg .= "Agradecemos pela preferência!";

        WhatsappService::send("+55{$order->customer_phone}", $msg);
    }

    private function buildOrderNotificationMessage($tenant, array $data, $order): string
    {
        $msg = "*Novo pedido recebido!*\n";
        $msg .= "*Cliente:* {$data['customer_name']}\n";
        $msg .= "*Telefone:* {$data['customer_phone']}\n";
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
}