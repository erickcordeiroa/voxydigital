<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderItems;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'items.variation'])->latest();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10);

        return Inertia::render('orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();
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

        $order = Order::with(['items.product', 'items.variation'])->find($order->id);

        $tenant = app('tenant');
        if ($tenant && $tenant->whatsapp) {
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
            $msg .= "*Taxa Entrega:* R$ " . number_format($tenant->tax_fixed / 100, 2, ',', '.'). "\n";
            $msg .= "*Total:* R$ " . number_format($data['total'] / 100, 2, ',', '.');

            WhatsappService::send("+55{$tenant->whatsapp}", $msg);
            WhatsappService::sendToClient("+55{$data['customer_phone']}");
        }
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|in:pending,preparing,delivering,delivered,canceled',
        ]);

        $order = Order::find($data['id']);
        $order->update([
            'status' => $data['status'],
        ]);

        switch($data['status']) {
            case 'pending':
                $status = 'Pendente';
                break;
            case 'preparing':
                $status = 'Preparando';
                break;
            case 'delivering':
                $status = 'Entregando';
                break;
            case 'delivered':
                $status = 'Entregue';
                break;
            case 'canceled':
                $status = 'Cancelado';
                break;
        }

        $msg = "*Pedido atualizado!*\n";
        $msg .= "O seu pedido foi atualizado para *{$status}*.\n";
        $msg .= "Agradecemos pela preferência!";
        
         WhatsappService::send("+55{$order->customer_phone}", $msg);

        return redirect()->back()->with('success', 'Pedido atualizado com sucesso!');
    }
}
