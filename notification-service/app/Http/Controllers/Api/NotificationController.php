<?php

namespace App\Http\Controllers\Api;

use App\Data\NotificationData;
use App\Enums\NotificationChannelType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationChannelResolver;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly NotificationChannelResolver $channelResolver,
    ) {}

    public function store(
        StoreNotificationRequest $request,
    ): NotificationResource {
        $data = new NotificationData(
            userId: (string) $request->integer('user_id'),
            type: $request->string('type')->toString(),
            channel: NotificationChannelType::from(
                $request->string('channel')->toString()
            ),
            message: $request->string('message')->toString(),
            subject: $request->input('subject'),
            referenceType: $request->input('reference_type'),
            referenceId: $request->integer('reference_id') ?: null,
        );

        $channel = $this->channelResolver->resolve(
            $data->channel,
        );

        return new NotificationResource(
            $this->notificationService->send(
                $data,
                $channel,
            )
        );
    }
}
