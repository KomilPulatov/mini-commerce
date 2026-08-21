<?php

namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Services\PaymentGateways\FakePaymentGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PaymentGateway::class,
            FakePaymentGateway::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
