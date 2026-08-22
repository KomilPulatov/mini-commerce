<?php

namespace App\Services\Channels;

use App\Contracts\NotificationChannel;
use App\Data\NotificationData;
use Illuminate\Support\Facades\Log;

class SmsNotificationChannel implements NotificationChannel
{
    public function send(NotificationData $data): void
    {
        Log::info('SMS notification sent', [
            'user_id' => $data->userId,
            'message' => $data->message,
        ]);
    }
}
