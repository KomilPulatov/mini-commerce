<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => null,
            'product_id' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(1, 10),
            'price' => fake()->numberBetween(100, 10000),
        ];
    }
}
