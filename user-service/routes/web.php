<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/docs');

Route::get('/docs/spec', function () {
    return response()->json([
        'openapi' => '3.0.3',
        'info' => [
            'title' => 'User Service API',
            'version' => '1.0.0',
        ],
        'paths' => [],
    ]);
});
