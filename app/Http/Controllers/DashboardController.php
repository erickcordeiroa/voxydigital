<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Consultas básicas (mantidas como estão)
        $totalProducts = Product::count();
        $totalOrdersReceived = Order::count();
        $totalOrderValues = Order::sum('total');
        $averageOrderValue = $totalOrdersReceived > 0 ? $totalOrderValues / $totalOrdersReceived : 0;
        $recentOrders = Order::latest()->take(10)->get();

        // Consulta unificada para dados semanais
        $weeklyStats = Order::selectRaw('
                DAYNAME(created_at) as day, 
                COUNT(*) as order_count, 
                SUM(total) as revenue
            ')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();

        // Processar os dados semanais
        $weeklyOrdersData = $this->formatWeeklyData($weeklyStats, 'order_count');
        $revenueData = $this->formatWeeklyData($weeklyStats, 'revenue');

        return Inertia::render('Dashboard', [
            'totalProducts' => $totalProducts,
            'totalOrdersReceived' => $totalOrdersReceived,
            'totalOrderValues' => $totalOrderValues,
            'averageOrderValue' => $averageOrderValue,
            'recentOrders' => $recentOrders,
            'weeklyOrdersData' => $weeklyOrdersData,
            'revenueData' => $revenueData,
        ]);
    }

    /**
     * Formata os dados semanais para o formato esperado pelo frontend
     */
    private function formatWeeklyData($weeklyStats, $valueField)
    {
        return $weeklyStats->map(function ($item) use ($valueField) {
            return [
                'name' => $this->translateDay($item->day),
                'value' => $valueField === 'revenue' ? (float) $item->{$valueField} : (int) $item->{$valueField}
            ];
        })->toArray();
    }

    /**
     * Traduz o nome do dia para abreviação em português
     */
    private function translateDay($day)
    {
        $days = [
            'Monday' => 'Seg',
            'Tuesday' => 'Ter',
            'Wednesday' => 'Qua',
            'Thursday' => 'Qui',
            'Friday' => 'Sex',
            'Saturday' => 'Sáb',
            'Sunday' => 'Dom',
        ];

        return $days[$day] ?? $day;
    }
}