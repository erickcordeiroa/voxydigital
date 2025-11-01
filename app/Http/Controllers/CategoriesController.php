<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Services\CategoryService;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoriesController extends Controller
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): Response
    {
        $categories = $this->service->paginate(15);
        return Inertia::render('categories/Index', ['categories' => $categories]);
    }

    public function show(string $slug): Response
    {
        $category = $this->service->findBySlug($slug);
        return Inertia::render('categories/Show', ['category' => $category]);
    }

    public function showPublic(string $slug): Response
    {
        $category = Category::where('slug', $slug)
            ->where('tenant_id', app('tenant_id'))
            ->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('status', true)
            ->where('tenant_id', app('tenant_id'))
            ->with(['category', 'variations', 'images'])
            ->orderBy('name')
            ->get();

        $categories = Category::where('tenant_id', app('tenant_id'))->get();

        return Inertia::render('public/categories/Show', [
            'category' => $category,
            'products' => $products,
            'categories' => $categories,
            'tenant' => app('tenant'),
        ]);
    }

    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());
        $categories = $this->service->paginate(15);
        
        return redirect()->route('categories.index')->with([
            'categories' => $categories
        ]);
    }

    public function update(int $id, CreateCategoryRequest $request): RedirectResponse
    {
        $this->service->update($id, $request->validated());
        $categories = $this->service->paginate(15);
        
        return redirect()->route('categories.index')->with([
            'categories' => $categories
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);
        $categories = $this->service->paginate(15);
        
        return redirect()->route('categories.index')->with([
            'categories' => $categories
        ]);
    }
}