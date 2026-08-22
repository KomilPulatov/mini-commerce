<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ServiceProxy
{
    public function forward(
        Request $request,
        string $service,
    ): SymfonyResponse {
        $baseUrl = $this->getBaseUrl($service);

        /*
         * Remove /api from the gateway path because the
         * internal service already exposes its own /api routes.
         *
         * Example:
         *
         * Gateway:
         *   /api/orders/1
         *
         * Internal:
         *   https://order-service.lc/api/orders/1
         */
        $path = $request->path();

        $url = rtrim($baseUrl, '/') . '/' . $path;

        $headers = [
            'Accept' => 'application/json',
        ];

        /*
         * Forward the original authorization token.
         */
        if ($request->hasHeader('Authorization')) {
            $headers['Authorization'] = $request->header('Authorization');
        }

        /*
         * Pass authenticated user identity to the internal service.
         *
         * The internal service can use this instead of having
         * to authenticate the token itself.
         */
        $user = $request->attributes->get('user');

        if (is_array($user) && isset($user['id'])) {
            $headers['X-User-Id'] = (string) $user['id'];
        }

        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->send(
                    $request->method(),
                    $url,
                    [
                        'query' => $request->query(),
                        'json' => $request->all(),
                    ],
                );
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => 'Service unavailable.',
            ], 503);
        }

        return $this->toResponse($response);
    }

    private function getBaseUrl(string $service): string
    {
        return match ($service) {
            'user' => config('services.user.url'),
            'product' => config('services.product.url'),
            'order' => config('services.order.url'),
            'payment' => config('services.payment.url'),

            default => throw new \InvalidArgumentException(
                "Unknown service [{$service}]."
            ),
        };
    }

    private function toResponse(Response $response): SymfonyResponse
    {
        return response(
            $response->body(),
            $response->status(),
        )->withHeaders([
            'Content-Type' => $response->header(
                'Content-Type',
                'application/json',
            ),
        ]);
    }
}
