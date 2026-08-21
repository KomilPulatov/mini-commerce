<?php

namespace App\Http\Controllers\Api;

use App\Data\CreatePaymentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $data = new CreatePaymentData(
            orderId: $request->integer('order_id'),
            amount: (string) $request->input('amount'),
            idempotencyKey: $request->header('Idempotency-Key'),
        );

        return new PaymentResource(
            $this->paymentService->process($data)
        );
    }
}
