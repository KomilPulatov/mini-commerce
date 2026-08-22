<?php

namespace App\Listeners;

use App\Data\NotificationData;
use App\Enums\NotificationChannelType;
use App\Events\OrderCreated;
use App\Services\NotificationChannelResolver;
use App\Services\NotificationService;

class SendOrderCreatedNotification
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly NotificationChannelResolver $channelResolver,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $data = new NotificationData(
            userId: (string) $event->userId,
            type: 'order_created',
            channel: NotificationChannelType::Email,
            message: "Your order #{$event->orderId} has been created.",
            subject: 'Order created',
            referenceType: 'order',
            referenceId: $event->orderId,
        );

        $channel = $this->channelResolver->resolve(
            $data->channel,
        );

        $this->notificationService->send(
            $data,
            $channel,
        );
    }
}
