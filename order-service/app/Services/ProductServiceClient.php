<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ProductServiceClient
{
    public function getProducts(array $productIds): array
    {
        $response = Http::timeout(3)
            ->get(
                config('services.product_service.url').'/api/products',
                [
                    'ids' => $productIds,
                ]
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Product Service is unavailable.'
            );
        }

        return $response->json('data', []);
    }
}
