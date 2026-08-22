<?php

namespace App\Models;

use App\Enums\NotificationChannelType;
use App\Enums\NotificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'channel',
        'status',
        'subject',
        'message',
        'reference_type',
        'reference_id',
    ];

    protected function casts(): array
    {
        return [
            'channel' => NotificationChannelType::class,
            'status' => NotificationStatus::class,
            'reference_id' => 'integer',
        ];
    }
}
