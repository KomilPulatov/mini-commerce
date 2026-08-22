<?php

namespace App\Listeners;

use App\Data\NotificationData;
use App\Enums\NotificationChannelType;
use App\Events\PaymentCompleted;
use App\Services\NotificationChannelResolver;
use App\Services\NotificationService;

class SendPaymentCompletedNotification
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly NotificationChannelResolver $channelResolver,
    ) {}

    public function handle(PaymentCompleted $event): void
    {
        $data = new NotificationData(
            userId: (string) $event->userId,
            type: 'payment_completed',
            channel: NotificationChannelType::Email,
            message: "Payment for order #{$event->orderId} was completed.",
            subject: 'Payment completed',
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
