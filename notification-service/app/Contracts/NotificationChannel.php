<?php

namespace App\Contracts;

use App\Data\NotificationData;

interface NotificationChannel
{
    public function send(NotificationData $data): void;
}
