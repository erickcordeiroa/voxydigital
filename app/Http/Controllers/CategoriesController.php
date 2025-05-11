<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoriesController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'categories/Index',
            ["categories" => Category::paginate(15)]
        );
    }

    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
    }

    public function update(int $id, CreateCategoryRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);
        Category::find($id)->update($data);
    }

    public function destroy(int $id){
        Category::find($id)->delete();
    }
}
