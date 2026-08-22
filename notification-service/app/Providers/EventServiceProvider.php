<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\PaymentCompleted;
use App\Listeners\SendOrderCreatedNotification;
use App\Listeners\SendPaymentCompletedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            SendOrderCreatedNotification::class,
        ],

        PaymentCompleted::class => [
            SendPaymentCompletedNotification::class,
        ],
    ];
}
