<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): Response
    {
        $data = $this->orderService->getPaginatedOrders($request->only(['search']));

        return Inertia::render('orders/Index', [
            'orders' => $data['orders'],
            'filters' => $data['filters'],
        ]);
    }

    public function store(CreateOrderRequest $request)
    {
        $this->orderService->createOrderWithNotification($request->validated());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|in:pending,preparing,delivering,delivered,canceled',
        ]);
        $this->orderService->updateOrderStatusWithNotification($data);

        return redirect()->back()->with('success', 'Pedido atualizado com sucesso!');
    }
}