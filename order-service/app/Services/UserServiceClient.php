<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class UserServiceClient
{
    public function exists(int $userId): bool
    {
        $response = Http::timeout(3)
            ->get(
                config('services.user_service.url') . "/api/users/{$userId}"
            );

        if ($response->status() === 404) {
            return false;
        }

        if ($response->failed()) {
            throw new RuntimeException(
                'User Service is unavailable.'
            );
        }

        return true;
    }
}
