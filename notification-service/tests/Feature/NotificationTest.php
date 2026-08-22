<?php

use App\Enums\NotificationChannelType;
use App\Enums\NotificationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sends an email notification', function () {
    $response = $this->postJson('/api/notifications', [
        'user_id' => 1,
        'type' => 'order_created',
        'channel' => 'email',
        'subject' => 'Order created',
        'message' => 'Your order has been created.',
        'reference_type' => 'order',
        'reference_id' => 10,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.user_id', '1')
        ->assertJsonPath(
            'data.channel',
            NotificationChannelType::Email->value
        )
        ->assertJsonPath(
            'data.status',
            NotificationStatus::Sent->value
        );

    $this->assertDatabaseHas('notifications', [
        'user_id' => '1',
        'type' => 'order_created',
        'channel' => 'email',
        'status' => 'sent',
        'reference_type' => 'order',
        'reference_id' => 10,
    ]);
});

it('sends an sms notification', function () {
    $response = $this->postJson('/api/notifications', [
        'user_id' => 1,
        'type' => 'order_created',
        'channel' => 'sms',
        'message' => 'Your order has been created.',
    ]);

    $response->assertCreated()
        ->assertJsonPath(
            'data.channel',
            NotificationChannelType::Sms->value
        )
        ->assertJsonPath(
            'data.status',
            NotificationStatus::Sent->value
        );
});

it('sends a push notification', function () {
    $response = $this->postJson('/api/notifications', [
        'user_id' => 1,
        'type' => 'payment_completed',
        'channel' => 'push',
        'message' => 'Your payment was completed.',
    ]);

    $response->assertCreated()
        ->assertJsonPath(
            'data.channel',
            NotificationChannelType::Push->value
        )
        ->assertJsonPath(
            'data.status',
            NotificationStatus::Sent->value
        );
});

it('validates the notification request', function () {
    $response = $this->postJson('/api/notifications', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'user_id',
            'type',
            'channel',
            'message',
        ]);
});

it('does not create a notification with an invalid channel', function () {
    $response = $this->postJson('/api/notifications', [
        'user_id' => 1,
        'type' => 'order_created',
        'channel' => 'telegram',
        'message' => 'Hello',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'channel',
        ]);
});
