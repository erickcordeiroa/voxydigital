<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariation\ProductVariationRequest;
use App\Services\ProductVariationService;

class ProductVariationController extends Controller
{
    private ProductVariationService $service;

    public function __construct(ProductVariationService $service)
    {
        $this->service = $service;
    }

    public function store(ProductVariationRequest $request)
    {
        $this->service->createVariation($request->validated());
    }

    public function update(ProductVariationRequest $request)
    {
        $result = $this->service->updateVariation($request->validated());

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 422);
        }
    }

    public function destroy($id)
    {
        $this->service->deleteVariation($id);
    }
}