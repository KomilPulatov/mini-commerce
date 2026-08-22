<?php

use App\Http\Controllers\Api\GatewayController;
use App\Http\Middleware\AuthenticateUser;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthenticateUser::class)->group(function () {

    Route::any('/users/{path?}', [GatewayController::class, 'user'])
        ->where('path', '.*');

    Route::any('/products/{path?}', [GatewayController::class, 'product'])
        ->where('path', '.*');

    Route::any('/orders/{path?}', [GatewayController::class, 'order'])
        ->where('path', '.*');

    Route::any('/payments/{path?}', [GatewayController::class, 'payment'])
        ->where('path', '.*');
});
