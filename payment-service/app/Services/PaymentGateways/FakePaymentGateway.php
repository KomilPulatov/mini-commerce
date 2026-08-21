<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGateway;
use App\Data\PaymentResult;
use RuntimeException;

class FakePaymentGateway implements PaymentGateway
{
    public function charge(
        int $orderId,
        string $amount,
    ): PaymentResult {
        return match (config('payment.fake_result')) {
            'success' => new PaymentResult(
                successful: true,
                transactionId: 'fake_' . uniqid(),
            ),

            'failure' => new PaymentResult(
                successful: false,
                error: 'Fake payment failed.',
            ),

            'timeout' => throw new RuntimeException(
                'Fake payment gateway timed out.'
            ),

            default => throw new RuntimeException(
                'Unknown fake payment result.'
            ),
        };
    }
}
