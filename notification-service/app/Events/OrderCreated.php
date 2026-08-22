<?php

namespace App\Events;

class OrderCreated
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $userId,
        public readonly string $total,
    ) {}
}
