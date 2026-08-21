<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Queries\ProductQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(
        IndexProductRequest $request
    ): AnonymousResourceCollection {
        $products = (new ProductQuery($request->validated()))
            ->paginate();

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->validated());

        return new ProductResource($product);
    }

    public function show(int $id): ProductResource
    {
        $product = Product::findOrFail($id);

        return new ProductResource($product);
    }

    public function update(
        UpdateProductRequest $request,
        int $id
    ): ProductResource {
        $product = Product::findOrFail($id);

        $product->update($request->validated());

        return new ProductResource($product->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(null, 204);
    }
}
