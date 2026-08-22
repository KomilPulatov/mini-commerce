<?php

use App\Contracts\PaymentGateway;
use App\Data\PaymentResult;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('processes a successful payment', function () {
    config(['payment.fake_result' => 'success']);

    $response = $this->withHeaders([
        'Idempotency-Key' => 'payment-123',
    ])->postJson('/api/payments', [
        'order_id' => 1,
        'amount' => 100.00,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('payments', [
        'order_id' => 1,
        'amount' => 100.00,
        'status' => PaymentStatus::COMPLETED->value,
    ]);

    expect(Payment::count())->toBe(1);
});

it('marks the payment as failed when the gateway fails', function () {
    config(['payment.fake_result' => 'failure']);

    $response = $this->withHeaders([
        'Idempotency-Key' => 'payment-124',
    ])->postJson('/api/payments', [
        'order_id' => 1,
        'amount' => 100.00,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('payments', [
        'order_id' => 1,
        'amount' => 100.00,
        'status' => PaymentStatus::FAILED->value,
    ]);
});

it('marks the payment as failed when the gateway times out', function () {
    config(['payment.fake_result' => 'timeout']);

    $response = $this->withHeaders([
        'Idempotency-Key' => 'payment-125',
    ])->postJson('/api/payments', [
        'order_id' => 1,
        'amount' => 100.00,
    ]);

    $response->assertServerError();

    $this->assertDatabaseHas('payments', [
        'order_id' => 1,
        'amount' => 100.00,
        'status' => PaymentStatus::FAILED->value,
    ]);
});

it('requires an idempotency key', function () {
    $response = $this->postJson('/api/payments', [
        'order_id' => 1,
        'amount' => 100.00,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'idempotency_key',
        ]);
});

it('validates the payment request', function () {
    $response = $this->withHeaders([
        'Idempotency-Key' => 'payment-126',
    ])->postJson('/api/payments', [
        'order_id' => 0,
        'amount' => 0,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'order_id',
            'amount',
        ]);
});

it('returns the existing payment for the same idempotency key', function () {
    config(['payment.fake_result' => 'success']);

    $gateway = Mockery::mock(PaymentGateway::class);

    $gateway->shouldReceive('charge')
        ->once()
        ->with(1, '100')
        ->andReturn(
            new PaymentResult(
                successful: true,
                transactionId: 'fake_transaction_123',
            )
        );

    $this->app->instance(PaymentGateway::class, $gateway);

    $headers = [
        'Idempotency-Key' => 'payment-127',
    ];

    $firstResponse = $this->withHeaders($headers)
        ->postJson('/api/payments', [
            'order_id' => 1,
            'amount' => 100,
        ]);

    $firstResponse->assertCreated();

    $secondResponse = $this->withHeaders($headers)
        ->postJson('/api/payments', [
            'order_id' => 1,
            'amount' => 100,
        ]);

    $secondResponse->assertOk();

    expect(Payment::count())->toBe(1);

    $this->assertDatabaseHas('payments', [
        'idempotency_key' => 'payment-127',
        'status' => PaymentStatus::COMPLETED->value,
        'transaction_id' => 'fake_transaction_123',
    ]);
});

it('uses the PaymentGateway abstraction', function () {
    $gateway = Mockery::mock(PaymentGateway::class);

    $gateway->shouldReceive('charge')
        ->once()
        ->with(1, '100')
        ->andReturn(
            new PaymentResult(
                successful: true,
                transactionId: 'fake_transaction_456',
            )
        );

    $this->app->instance(PaymentGateway::class, $gateway);

    $response = $this->withHeaders([
        'Idempotency-Key' => 'payment-128',
    ])->postJson('/api/payments', [
        'order_id' => 1,
        'amount' => 100,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('payments', [
        'order_id' => 1,
        'status' => PaymentStatus::COMPLETED->value,
        'transaction_id' => 'fake_transaction_456',
    ]);
});
