<?php

namespace App\Enums;

enum NotificationChannelType: string
{
    case Email = 'email';
    case Sms = 'sms';
    case Push = 'push';
}
