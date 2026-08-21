<?php

namespace App\Contracts;

use App\Data\PaymentResult;

interface PaymentGateway
{
    public function charge(
        int $orderId,
        string $amount,
    ): PaymentResult;
}
