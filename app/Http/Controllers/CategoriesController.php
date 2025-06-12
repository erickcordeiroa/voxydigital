<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Services\CategoryService;
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

    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());
        return redirect()->route('categories.index');
    }

    public function update(int $id, CreateCategoryRequest $request): RedirectResponse
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('categories.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);
        return redirect()->route('categories.index');
    }
}