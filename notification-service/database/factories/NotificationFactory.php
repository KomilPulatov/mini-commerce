<?php

namespace Database\Factories;

use App\Enums\NotificationChannelType;
use App\Enums\NotificationStatus;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => (string) fake()->numberBetween(1, 100),
            'type' => 'order_created',
            'channel' => NotificationChannelType::Email,
            'status' => NotificationStatus::Sent,
            'subject' => 'Order created',
            'message' => 'Your order has been created.',
            'reference_type' => 'order',
            'reference_id' => fake()->numberBetween(1, 1000),
        ];
    }
}
