<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariation\ProductVariationRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    public function store(ProductVariationRequest $request)
    {
        $data = $request->validated();
        ProductVariation::create([
            'product_id' => $data['product_id'],
            'sku' => $data['sku'],
            'size' => $data['size'],
            'reference' => $data['reference'],
        ]);
    }

    public function update(ProductVariationRequest $request)
    {
        $variation = ProductVariation::find($request->input('id'));
        $data = $request->validated();

        // Garante unicidade do SKU por produto, exceto para o próprio registro
        $exists = ProductVariation::where('product_id', $data['product_id'])
            ->where('sku', $data['sku'])
            ->where('id', '!=', $variation->id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'O SKU já existe para este produto.'], 422);
        }

        $variation->update([
            'sku' => $data['sku'],
            'size' => $data['size'],
            'reference' => $data['reference'],
        ]);
    }

    public function destroy($id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();
    }
}
