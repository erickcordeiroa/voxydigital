<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product')->latest();

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
            'tenant_id' => $data['tenant_id'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'delivery_address' => $data['delivery_address'],
            'note' => $data['note'],
            'total' => $data['total'],
            'status' => 'pending',
        ]);

        foreach ($data['items'] as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }
}
