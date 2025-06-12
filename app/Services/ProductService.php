<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function getPaginatedProducts(array $filters): array
    {
        $query = Product::with(['category', 'images']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        $products = $query->paginate(15)->withQueryString();

        $products->getCollection()->transform(function ($product) {
            $product->uri = $product->uri ? Storage::url($product->uri) : null;
            return $product;
        });

        return [
            'products' => $products,
            'categories' => Category::all(),
            'filters' => $filters,
        ];
    }

    public function getProductShowData(string $slug): array
    {
        $product = Product::with(['category', 'images', 'variations'])
            ->where('slug', $slug)->first();

        return [
            'product' => $product,
            'tenant' => app('tenant'),
            'categories' => Category::all(),
        ];
    }

    public function getProductEditData(int $id): array
    {
        $product = Product::with(['category', 'images', 'variations'])->find($id);

        if ($product && $product->images) {
            foreach ($product->images as $image) {
                $image->uri = Storage::url($image->uri);
            }
        }

        return [
            'product' => $product,
            'categories' => Category::all(),
        ];
    }

    public function createProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug . '-' . time();
            $data['slug'] = $slug;
            $data['tenant_id'] = app('tenant_id');

            $product = Product::create($data);

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $uri = $this->uploadImage($image);
                    $product->images()->create([
                        'uri' => $uri,
                        'thumbnail' => $index === 0,
                    ]);
                    if ($index === 0) {
                        $product->update(['uri' => $uri]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateProduct(int $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::with('images')->find($id);
            $data = $request->all();

            $product->update($data);

            $existingImages = $request->input('existing_images', []);
            $existingImages = array_map(function ($uri) {
                return preg_replace('#^/storage/#', '', $uri);
            }, $existingImages);

            foreach ($product->images as $image) {
                if (!in_array($image->uri, $existingImages)) {
                    $this->deleteImage($image->uri);
                    $image->delete();
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $uri = $this->uploadImage($file);
                    $product->images()->create([
                        'uri' => $uri,
                        'thumbnail' => false,
                    ]);
                    $existingImages[] = $uri;
                }
            }

            $firstImage = $product->images()->whereIn('uri', $existingImages)->first();
            if ($firstImage) {
                $product->update(['uri' => $firstImage->uri]);
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index')
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroyProduct(int $id)
    {
        try {
            $product = Product::with('images')->find($id);

            if ($product && $product->images) {
                foreach ($product->images as $image) {
                    $this->deleteImage($image->uri);
                }
            }

            if ($product) {
                $product->delete();
            }

            return redirect()->route('products.index')
                ->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    protected function uploadImage($image)
    {
        return $image->store('products', 'public');
    }

    protected function deleteImage($imagePath)
    {
        Storage::disk('public')->delete($imagePath);
    }
}