<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        $authorization = $request->header('Authorization');

        if (! $authorization) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $response = Http::withHeaders([
            'Authorization' => $authorization,
            'Accept' => 'application/json',
        ])
            ->timeout(5)
            ->get(config('services.auth.user_url'));

        if ($response->failed()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = $response->json();

        /*
         * Store the authenticated user on the request.
         *
         * We don't need an App\Models\User in the gateway.
         * The gateway does not own users.
         */
        $request->attributes->set('user', $user);

        return $next($request);
    }
}
