<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;

class HomeService
{
    public function getHomeData(): array
    {
        $tenant = app('tenant');
        
        // Busca dados diretamente do banco sem cache
        return $this->buildHomeData($tenant);
    }

    protected function buildHomeData(Tenant $tenant): array
    {
        // Carrega banners ativos
        $banners = Banner::where('tenant_id', $tenant->id)
            ->active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Carrega categorias com produtos (limitando a 10 produtos por categoria)
        $categories = Category::where('tenant_id', $tenant->id)
            ->whereHas('products', function ($query) {
                $query->where('status', true);
            })
            ->with(['products' => function ($query) {
                $query->where('status', true)->limit(10);
            }])
            ->get();

        // Carrega todos os produtos para o frontend (necessário para o filtro)
        $allProducts = Product::where('tenant_id', $tenant->id)
            ->where('status', true)
            ->with(['category', 'variations', 'images'])
            ->latest()
            ->get();

        // Retorna todas as informações necessárias para o frontend
        return [
            'banners' => $banners,
            'categories' => $categories,
            'products' => $allProducts,
            'tenant' => app('tenant'),
        ];
    }

    // Método removido - não há mais cache para limpar
}
