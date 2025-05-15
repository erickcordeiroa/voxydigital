<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(15)->withQueryString();

        // Adiciona o link público no campo 'uri'
        $products->getCollection()->transform(function ($product) {
            $product->uri = Storage::url($product->uri); // Gera o link público
            return $product;
        });

        return Inertia::render('products/Index', [
            'products' => $products,
            'categories' => Category::all(),
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    public function show(string $slug) {
        $product = Product::with('category')
                ->where('slug', $slug)->first();

        return Inertia::render('public/products/Index', [
            'product' => $product,
            'tenant' => app('tenant')
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['tenant_id'] = 1;

        if (isset($data['image'])) {
            $data['uri'] = $this->uploadImage($data['image']);
        }

        Product::create($data);
    }

    public function update(int $id, Request $request)
    {
        $product = Product::find($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Remove a imagem antiga, se existir
            if ($product->uri) {
                $this->deleteImage($product->uri);
            }

            $data['uri'] = $this->uploadImage($request->file('image'));
        }

        $product->update($data);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        // Remove a imagem vinculada, se existir
        if ($product->image) {
            $this->deleteImage($product->image);
        }

        $product->delete();
    }

    protected function uploadImage($image)
    {
        // Salva a imagem no diretório 'products' e retorna o caminho
        return $image->store('products', 'public');
    }

    protected function deleteImage($imagePath)
    {
        // Remove a imagem do armazenamento
        Storage::disk('public')->delete($imagePath);
    }
}
