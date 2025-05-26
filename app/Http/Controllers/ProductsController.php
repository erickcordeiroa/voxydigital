<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

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

    public function show(string $slug)
    {
        $product = Product::with(['category', 'images'])
            ->where('slug', $slug)->first();

        return Inertia::render('public/products/Index', [
            'product' => $product,
            'tenant' => app('tenant'),
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug . '-' . time();
            $data['slug'] = $slug;
            $data['tenant_id'] = app('tenant_id');

            // Cria o produto sem imagens primeiro
            $product = Product::create($data);

            // Se houver múltiplas imagens, salva cada uma em product_images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $uri = $this->uploadImage($image);
                    // Supondo que exista o relacionamento images() em Product para product_images
                    $product->images()->create([
                        'uri' => $uri,
                        'thumbnail' => $index === 0, // Marca a primeira imagem como principal (thumbnail)
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

    public function edit(int $id)
    {
        $product = Product::with(['category', 'images', 'variations'])->find($id);

        // Adiciona o link público para cada imagem em images
        if ($product->images) {
            foreach ($product->images as $image) {
                $image->uri = Storage::url($image->uri);
            }
        }

        return Inertia::render('products/Edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }
    public function update(int $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::with('images')->find($id);
            $data = $request->all();

            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug . '-' . time();
            $data['slug'] = $slug;

            // Atualiza os dados principais do produto
            $product->update($data);

            // URIs das imagens antigas que devem ser mantidas
            $existingImages = $request->input('existing_images', []);
            $existingImages = array_map(function ($uri) {
                return preg_replace('#^/storage/#', '', $uri);
            }, $existingImages);

            // Remove imagens que não estão mais na lista enviada
            foreach ($product->images as $image) {
                if (!in_array($image->uri, $existingImages)) {
                    $this->deleteImage($image->uri);
                    $image->delete();
                }
            }

            // Adiciona novas imagens
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $uri = $this->uploadImage($file);
                    $product->images()->create([
                        'uri' => $uri,
                        'thumbnail' => false, // ajuste conforme sua lógica
                    ]);
                    // Adiciona a nova imagem à lista de existentes para o thumbnail
                    $existingImages[] = $uri;
                }
            }

            // Atualiza a imagem principal (thumbnail) se necessário
            // Pega a primeira imagem da lista final (antigas mantidas + novas)
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

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            // Remove todas as imagens vinculadas ao produto
            if ($product->images) {
                foreach ($product->images as $image) {
                    $this->deleteImage($image->uri);
                }
            }

            $product->delete();
            return redirect()->route('products.index')
                ->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
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
