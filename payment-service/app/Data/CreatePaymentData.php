<?php

namespace App\Data;

final readonly class CreatePaymentData
{
    public function __construct(
        public int $orderId,
        public string $amount,
        public string $idempotencyKey,
    ) {}
}
