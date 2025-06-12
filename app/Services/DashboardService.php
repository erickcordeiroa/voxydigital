<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getDashboardData(): array
    {
        // Utilize cache para melhorar performance dos totais e agregações
        $totalProducts = Cache::remember('dashboard.total_products', 60, fn() => Product::count());
        $totalOrdersReceived = Cache::remember('dashboard.total_orders', 60, fn() => Order::count());
        $totalOrderValues = Cache::remember('dashboard.total_order_values', 60, fn() => (float) Order::sum('total'));

        $averageOrderValue = $totalOrdersReceived > 0 ? $totalOrderValues / $totalOrdersReceived : 0.0;

        // Recentes normalmente mudam, não cachear
        $recentOrders = Order::latest()->take(10)->get();

        // Dados semanais podem ser cacheados por 10 minutos
        $weeklyStats = Cache::remember('dashboard.weekly_stats', 10 * 60, function () {
            return Order::selectRaw('
                    DAYNAME(created_at) as day, 
                    COUNT(*) as order_count, 
                    SUM(total) as revenue
                ')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->groupBy('day')
                ->get();
        });

        $weeklyOrdersData = $this->formatWeeklyData($weeklyStats, 'order_count');
        $revenueData = $this->formatWeeklyData($weeklyStats, 'revenue');

        return [
            'totalProducts' => $totalProducts,
            'totalOrdersReceived' => $totalOrdersReceived,
            'totalOrderValues' => $totalOrderValues,
            'averageOrderValue' => $averageOrderValue,
            'recentOrders' => $recentOrders,
            'weeklyOrdersData' => $weeklyOrdersData,
            'revenueData' => $revenueData,
        ];
    }

    private function formatWeeklyData(Collection $weeklyStats, string $valueField): array
    {
        $daysOfWeek = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        // Garante que todos os dias estejam representados (mesmo se zerados)
        $statsByDay = $weeklyStats->keyBy('day');

        $result = [];
        foreach ($daysOfWeek as $day) {
            $item = $statsByDay->get($day);
            $result[] = [
                'name' => $this->translateDay($day),
                'value' => $item ? ($valueField === 'revenue' ? (float) $item->{$valueField} : (int) $item->{$valueField}) : 0
            ];
        }
        return $result;
    }

    private function translateDay(string $day): string
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