<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getDashboardData(): array
    {
        $tenantId = app('tenant_id');
        
        // Busca dados diretamente do banco com filtro de tenant_id para evitar full scan
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        $totalOrdersReceived = Order::where('tenant_id', $tenantId)->count();
        $totalOrderValues = (float) Order::where('tenant_id', $tenantId)->sum('total');

        $averageOrderValue = $totalOrdersReceived > 0 ? $totalOrderValues / $totalOrdersReceived : 0.0;

        // Busca pedidos recentes usando índice composto (tenant_id, status, created_at)
        $recentOrders = Order::where('tenant_id', $tenantId)
            ->latest()
            ->take(10)
            ->get();

        // Dados semanais - compatível com PostgreSQL
        // Usa índice composto (tenant_id, created_at) para melhor performance
        $weeklyStats = Order::selectRaw('
                    DAYNAME(created_at) as day, 
                    COUNT(*) as order_count, 
                    SUM(total) as revenue
                ')
            ->where('tenant_id', $tenantId)
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
            'monday   ',
            'tuesday  ',
            'wednesday',
            'thursday ',
            'friday   ',
            'saturday ',
            'sunday   '
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