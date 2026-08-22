<?php

namespace App\Data;

use App\Enums\NotificationChannelType;

class NotificationData
{
    public function __construct(
        public readonly string $userId,
        public readonly string $type,
        public readonly NotificationChannelType $channel,
        public readonly string $message,
        public readonly ?string $subject = null,
        public readonly ?string $referenceType = null,
        public readonly ?int $referenceId = null,
    ) {}
}
