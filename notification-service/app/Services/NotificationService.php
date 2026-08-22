<?php

namespace App\Services;

use App\Contracts\NotificationChannel;
use App\Data\NotificationData;
use App\Enums\NotificationStatus;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function send(
        NotificationData $data,
        NotificationChannel $channel,
    ): Notification {
        $notification = DB::transaction(function () use ($data) {
            return Notification::create([
                'user_id' => $data->userId,
                'type' => $data->type,
                'channel' => $data->channel,
                'status' => NotificationStatus::Pending,
                'subject' => $data->subject,
                'message' => $data->message,
                'reference_type' => $data->referenceType,
                'reference_id' => $data->referenceId,
            ]);
        });

        try {
            $channel->send($data);

            $notification->update([
                'status' => NotificationStatus::Sent,
            ]);

            return $notification->refresh();
        } catch (\Throwable $exception) {
            $notification->update([
                'status' => NotificationStatus::Failed,
            ]);

            throw $exception;
        }
    }
}
