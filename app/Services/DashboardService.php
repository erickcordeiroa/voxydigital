<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getDashboardData(): array
    {
        // Busca dados diretamente do banco sem cache
        $totalProducts = Product::count();
        $totalOrdersReceived = Order::count();
        $totalOrderValues = (float) Order::sum('total');

        $averageOrderValue = $totalOrdersReceived > 0 ? $totalOrderValues / $totalOrdersReceived : 0.0;

        // Busca pedidos recentes
        $recentOrders = Order::latest()->take(10)->get();

        // Dados semanais - compatível com PostgreSQL
        $weeklyStats = Order::selectRaw('
                    TO_CHAR(created_at, \'Day\') as day, 
                    COUNT(*) as order_count, 
                    SUM(total) as revenue
                ')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->groupBy('day')
                ->get();

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
        // PostgreSQL retorna nomes dos dias em português (com espaços extras)
        $daysOfWeek = [
            'monday   ', 'tuesday  ', 'wednesday', 'thursday ', 'friday   ', 'saturday ', 'sunday   '
        ];

        // Garante que todos os dias estejam representados (mesmo se zerados)
        $statsByDay = $weeklyStats->keyBy(function ($item) {
            return trim($item->day); // Remove espaços extras
        });

        $result = [];
        foreach ($daysOfWeek as $day) {
            $cleanDay = trim($day);
            $item = $statsByDay->get($cleanDay);
            $result[] = [
                'name' => $this->translateDay($cleanDay),
                'value' => $item ? ($valueField === 'revenue' ? (float) $item->{$valueField} : (int) $item->{$valueField}) : 0
            ];
        }
        return $result;
    }

    private function translateDay(string $day): string
    {
        $days = [
            'monday' => 'Seg',
            'tuesday' => 'Ter',
            'wednesday' => 'Qua',
            'thursday' => 'Qui',
            'friday' => 'Sex',
            'saturday' => 'Sáb',
            'sunday' => 'Dom',
        ];

        return $days[strtolower($day)] ?? $day;
    }
}