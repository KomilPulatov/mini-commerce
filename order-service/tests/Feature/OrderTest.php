<?php

use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\ProductServiceClient;
use App\Services\UserServiceClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('creates an order', function () {
    $userService = Mockery::mock(UserServiceClient::class);
    $userService
        ->shouldReceive('exists')
        ->once()
        ->with(1)
        ->andReturnTrue();

    $productService = Mockery::mock(ProductServiceClient::class);
    $productService
        ->shouldReceive('getProducts')
        ->once()
        ->with([10])
        ->andReturn([
            [
                'id' => 10,
                'price' => 100,
                'is_active' => true,
            ],
        ]);

    $this->app->instance(UserServiceClient::class, $userService);
    $this->app->instance(ProductServiceClient::class, $productService);

    Event::fake();

    $order = app(OrderService::class)->createOrder(
        1,
        [
            [
                'product_id' => 10,
                'quantity' => 2,
            ],
        ],
    );

    expect($order->user_id)->toBe(1)
        ->and($order->status)->toBe(OrderStatus::Pending)
        ->and($order->total)->toBe(200);

    expect($order->items)->toHaveCount(1);

    expect($order->items->first()->product_id)->toBe(10)
        ->and($order->items->first()->quantity)->toBe(2)
        ->and($order->items->first()->price)->toBe(100);

    Event::assertDispatched(OrderCreated::class);
});

it('does not create an order for a non existing user', function () {
    $userService = Mockery::mock(UserServiceClient::class);
    $userService
        ->shouldReceive('exists')
        ->once()
        ->with(999)
        ->andReturnFalse();

    $this->app->instance(UserServiceClient::class, $userService);

    expect(fn () => app(OrderService::class)->createOrder(
        999,
        [
            [
                'product_id' => 10,
                'quantity' => 1,
            ],
        ],
    ))->toThrow(ValidationException::class);

    expect(Order::count())->toBe(0);
});

it('does not create an order when a product does not exist', function () {
    $userService = Mockery::mock(UserServiceClient::class);
    $userService
        ->shouldReceive('exists')
        ->once()
        ->with(1)
        ->andReturnTrue();

    $productService = Mockery::mock(ProductServiceClient::class);
    $productService
        ->shouldReceive('getProducts')
        ->once()
        ->with([999])
        ->andReturn([]);

    $this->app->instance(UserServiceClient::class, $userService);
    $this->app->instance(ProductServiceClient::class, $productService);

    expect(fn () => app(OrderService::class)->createOrder(
        1,
        [
            [
                'product_id' => 999,
                'quantity' => 1,
            ],
        ],
    ))->toThrow(ValidationException::class);

    expect(Order::count())->toBe(0);
});

it('does not create an order with an inactive product', function () {
    $userService = Mockery::mock(UserServiceClient::class);
    $userService
        ->shouldReceive('exists')
        ->once()
        ->with(1)
        ->andReturnTrue();

    $productService = Mockery::mock(ProductServiceClient::class);
    $productService
        ->shouldReceive('getProducts')
        ->once()
        ->with([10])
        ->andReturn([
            [
                'id' => 10,
                'price' => 100,
                'is_active' => false,
            ],
        ]);

    $this->app->instance(UserServiceClient::class, $userService);
    $this->app->instance(ProductServiceClient::class, $productService);

    expect(fn () => app(OrderService::class)->createOrder(
        1,
        [
            [
                'product_id' => 10,
                'quantity' => 1,
            ],
        ],
    ))->toThrow(ValidationException::class);

    expect(Order::count())->toBe(0);
});

it('calculates the order total correctly', function () {
    $userService = Mockery::mock(UserServiceClient::class);
    $userService
        ->shouldReceive('exists')
        ->andReturnTrue();

    $productService = Mockery::mock(ProductServiceClient::class);
    $productService
        ->shouldReceive('getProducts')
        ->andReturn([
            [
                'id' => 10,
                'price' => 100,
                'is_active' => true,
            ],
            [
                'id' => 20,
                'price' => 50,
                'is_active' => true,
            ],
        ]);

    $this->app->instance(UserServiceClient::class, $userService);
    $this->app->instance(ProductServiceClient::class, $productService);

    $order = app(OrderService::class)->createOrder(
        1,
        [
            [
                'product_id' => 10,
                'quantity' => 2,
            ],
            [
                'product_id' => 20,
                'quantity' => 3,
            ],
        ],
    );

    expect($order->total)->toBe(350);
});

it('lists orders', function () {
    Order::factory()->count(3)->create();

    $response = $this->getJson('/api/orders');

    $response->assertOk();
});

it('shows an order', function () {
    $order = Order::factory()->create();

    $response = $this->getJson(
        "/api/orders/{$order->id}"
    );

    $response->assertOk()
        ->assertJsonPath('data.id', $order->id);
});

it('cancels a pending order', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::Pending,
    ]);

    $response = $this->postJson(
        "/api/orders/{$order->id}/cancel"
    );

    $response->assertOk();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => OrderStatus::Cancelled->value,
    ]);
});

it('cannot cancel a completed order', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::Completed,
    ]);

    $response = $this->postJson(
        "/api/orders/{$order->id}/cancel"
    );

    $response->assertUnprocessable();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => OrderStatus::Completed->value,
    ]);
});

it('cannot cancel an already cancelled order', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::Cancelled,
    ]);

    $response = $this->postJson(
        "/api/orders/{$order->id}/cancel"
    );

    $response->assertUnprocessable();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => OrderStatus::Cancelled->value,
    ]);
});
