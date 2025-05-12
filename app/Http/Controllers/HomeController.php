<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        return Inertia::render(
            'public/home/Index',
            [
                "products" => Product::with('category')->where('status', true)
                    ->whereHas('category', function ($query) {
                        $query->where('status', true);
                    })->get(),
                "categories" => Category::with('products')->where('status', true)
                    ->whereHas('products', function($query) {
                        $query->where('status', true);
                    })->get(),
                "tenant" => Tenant::find(app('tenant_id'))
            ]
        );
    }
}
