<?php

namespace App\Services;

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

    // Método removido - não há mais cache para limpar
}
