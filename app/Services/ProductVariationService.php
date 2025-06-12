<?php

namespace App\Services;

use App\Models\ProductVariation;

class ProductVariationService
{
    public function createVariation(array $data)
    {
        return ProductVariation::create([
            'product_id' => $data['product_id'],
            'sku' => $data['sku'],
            'size' => $data['size'],
            'reference' => $data['reference'],
        ]);
    }

    public function updateVariation(array $data): array
    {
        $variation = ProductVariation::find($data['id']);

        // Garante unicidade do SKU por produto, exceto para o próprio registro
        $exists = ProductVariation::where('product_id', $data['product_id'])
            ->where('sku', $data['sku'])
            ->where('id', '!=', $variation->id)
            ->exists();

        if ($exists) {
            return ['error' => 'O SKU já existe para este produto.'];
        }

        $variation->update([
            'sku' => $data['sku'],
            'size' => $data['size'],
            'reference' => $data['reference'],
        ]);

        return ['success' => true];
    }

    public function deleteVariation(int $id): void
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();
    }
}