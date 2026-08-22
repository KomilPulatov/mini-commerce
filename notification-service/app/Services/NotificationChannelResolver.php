<?php

namespace App\Services;

use App\Contracts\NotificationChannel;
use App\Enums\NotificationChannelType;
use App\Services\Channels\EmailNotificationChannel;
use App\Services\Channels\PushNotificationChannel;
use App\Services\Channels\SmsNotificationChannel;
use InvalidArgumentException;

class NotificationChannelResolver
{
    public function resolve(
        NotificationChannelType $channel,
    ): NotificationChannel {
        return match ($channel) {
            NotificationChannelType::Email =>
            app(EmailNotificationChannel::class),

            NotificationChannelType::Sms =>
            app(SmsNotificationChannel::class),

            NotificationChannelType::Push =>
            app(PushNotificationChannel::class),
        };
    }
}
