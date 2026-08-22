<?php

namespace App\Services\Channels;

use App\Contracts\NotificationChannel;
use App\Data\NotificationData;
use Illuminate\Support\Facades\Log;

class EmailNotificationChannel implements NotificationChannel
{
    public function send(NotificationData $data): void
    {
        Log::info('Email notification sent', [
            'user_id' => $data->userId,
            'subject' => $data->subject,
            'message' => $data->message,
        ]);
    }
}
