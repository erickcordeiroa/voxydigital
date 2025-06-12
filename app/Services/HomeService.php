<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;

class HomeService
{
    public function getHomeData(): array
    {
        $tenant = app('tenant');
        $cacheKey = "home_cache:{$tenant->domain}";

        // Tenta buscar do Redis
        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($tenant) {
            return $this->buildHomeData($tenant);
        });
    }

    protected function buildHomeData(Tenant $tenant): array
    {
        // Carrega categorias com produtos
        $categories = Category::where('tenant_id', $tenant->id)
            ->whereHas('products', function ($query) {
                $query->where('status', true);
            })
            ->with(['products' => function ($query) {
                $query->where('status', true);
            }])
            ->get();

        // Carrega produtos (com categoria, se necessário)
        $products = Product::where('tenant_id', $tenant->id)
            ->latest()
            ->get();

        // Retorna todas as informações necessárias para o frontend
        return [
            'categories' => $categories,
            'products' => $products,
            'tenant' => app('tenant'),
        ];
    }

    public function clearHomeCache(Tenant $tenant): void
    {
        Cache::forget("home_cache:{$tenant->slug}");
    }
}
