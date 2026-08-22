<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Data\CreatePaymentData;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use RuntimeException;

class PaymentService
{
    public function __construct(
        private readonly PaymentGateway $gateway,
    ) {}

    public function process(CreatePaymentData $data): Payment
    {
        $existingPayment = Payment::where(
            'idempotency_key',
            $data->idempotencyKey,
        )->first();

        if ($existingPayment) {
            return $existingPayment;
        }

        $payment = Payment::create([
            'order_id' => $data->orderId,
            'amount' => $data->amount,
            'status' => PaymentStatus::PENDING,
            'idempotency_key' => $data->idempotencyKey,
        ]);

        try {
            $result = $this->gateway->charge(
                $data->orderId,
                $data->amount,
            );

            if ($result->successful) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'transaction_id' => $result->transactionId,
                ]);

                return $payment->refresh();
            }

            $payment->update([
                'status' => PaymentStatus::FAILED,
            ]);

            return $payment->refresh();
        } catch (RuntimeException $exception) {
            $payment->update([
                'status' => PaymentStatus::FAILED,
            ]);

            throw $exception;
        }
    }
}
