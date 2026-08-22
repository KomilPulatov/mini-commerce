<?php

namespace App\Listeners;

use App\Contracts\NotificationChannel;
use App\Data\NotificationData;
use App\Enums\NotificationChannelType;
use App\Events\OrderCreated;
use App\Services\NotificationService;

class SendOrderCreatedNotification
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $data = new NotificationData(
            userId: (string) $event->userId,
            type: 'order_created',
            channel: NotificationChannelType::Email,
            subject: 'Order created',
            message: "Your order #{$event->orderId} has been created.",
            referenceType: 'order',
            referenceId: $event->orderId,
        );

        $channel = app(NotificationChannel::class);

        $this->notificationService->send(
            $data,
            $channel,
        );
    }
}
