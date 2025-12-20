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
        try {
            $order = $this->orderService->createOrderWithNotification($request->validated());
            
            $message = 'Pedido criado com sucesso';
            $paymentProcessed = !empty($order->payment_id);
            
            if (!$paymentProcessed) {
                $message = 'Pedido criado com sucesso. O pagamento será processado posteriormente.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'payment_processed' => $paymentProcessed,
                'data' => [
                    'order_id' => $order->id,
                    'payment_id' => $order->payment_id,
                    'payment_status' => $order->payment_status,
                    'qr_code' => $order->qr_code,
                    'qr_code_base64' => $order->qr_code_base64,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pedido: ' . $e->getMessage(),
            ], 400);
        }
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

    public function checkPayment($orderId)
    {
        $order = \App\Models\Order::find($orderId);
        
        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }
        
        return response()->json([
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
        ]);
    }
}