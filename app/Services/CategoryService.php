<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Category::paginate($perPage);
    }

    public function create(array $data): Category
    {
        $data['slug'] = $this->makeSlug($data['name']);
        return Category::create($data);
    }

    public function update(int $id, array $data): ?bool
    {
        $data['slug'] = $this->makeSlug($data['name']);
        $category = $this->findCategory($id);
        return $category?->update($data);
    }

    public function delete(int $id): ?bool
    {
        $category = $this->findCategory($id);
        return $category?->delete();
    }

    private function makeSlug(string $name): string
    {
        return Str::slug($name);
    }

    private function findCategory(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }
}