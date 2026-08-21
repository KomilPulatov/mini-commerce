<?php

namespace App\Data;

final readonly class PaymentResult
{
    public function __construct(
        public bool $successful,
        public ?string $transactionId = null,
        public ?string $error = null,
    ) {}
}
