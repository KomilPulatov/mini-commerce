<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private UserServiceClient $userService,
        private ProductServiceClient $productService,
    ) {}

    public function createOrder(
        int $userId,
        array $items,
    ): Order {
        if (! $this->userService->exists($userId)) {
            throw ValidationException::withMessages([
                'user_id' => ['The specified user does not exist.'],
            ]);
        }

        $productIds = collect($items)
            ->pluck('product_id')
            ->values()
            ->all();

        $products = $this->productService->getProducts($productIds);

        $productsById = collect($products)->keyBy('id');

        $orderItems = [];
        $total = 0;

        foreach ($items as $item) {
            $product = $productsById->get($item['product_id']);

            if (! $product) {
                throw ValidationException::withMessages([
                    'items' => [
                        "Product {$item['product_id']} was not found.",
                    ],
                ]);
            }

            if (! ($product['is_active'] ?? false)) {
                throw ValidationException::withMessages([
                    'items' => [
                        "Product {$item['product_id']} is not active.",
                    ],
                ]);
            }

            $quantity = $item['quantity'];
            $price = $product['price'];

            $subtotal = $price * $quantity;

            $total += $subtotal;

            $orderItems[] = [
                'product_id' => $product['id'],
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $order = DB::transaction(function () use (
            $userId,
            $total,
            $orderItems
        ) {
            $order = Order::create([
                'user_id' => $userId,
                'status' => OrderStatus::Pending,
                'total' => $total,
            ]);

            $order->items()->createMany($orderItems);

            return $order->load('items');
        });

        OrderCreated::dispatch($order);

        return $order;
    }

    public function cancel(Order $order): Order
    {
        if ($order->status !== OrderStatus::Pending) {
            throw ValidationException::withMessages([
                'status' => [
                    'Only pending orders can be cancelled.',
                ],
            ]);
        }

        $order->update([
            'status' => OrderStatus::Cancelled,
        ]);

        return $order->refresh()->load('items');
    }
}
