<?php

namespace App\Events;

class PaymentCompleted
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $userId,
        public readonly string $amount,
    ) {}
}
