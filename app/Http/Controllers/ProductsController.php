<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductsController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): Response
    {
        $data = $this->service->getPaginatedProducts($request->only(['search', 'category']));

        return Inertia::render('products/Index', [
            'products' => $data['products'],
            'categories' => $data['categories'],
            'filters' => $data['filters'],
        ]);
    }

    public function show(string $slug): Response
    {
        $data = $this->service->getProductShowData($slug);

        return Inertia::render('public/products/Index', $data);
    }

    public function create(): Response
    {
        return Inertia::render('products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        return $this->service->createProduct($request);
    }

    public function edit(int $id): Response
    {
        $data = $this->service->getProductEditData($id);

        return Inertia::render('products/Edit', $data);
    }

    public function update(int $id, Request $request)
    {
        return $this->service->updateProduct($id, $request);
    }

    public function destroy($id)
    {
        return $this->service->destroyProduct($id);
    }
}