<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\MessageData;
use App\Enums\MessageStatus;
use App\Jobs\SendMessageNotificationsJob;
use App\Models\Message;
use App\Services\MessageNotificationService;
use Illuminate\Support\Facades\Schema;

final class CreateMessage
{
    private static ?bool $hasAttachmentsColumn = null;

    public function __construct(
        private readonly MessageNotificationService $notificationService,
    ) {}

    public function execute(MessageData $data, bool $syncNotifications = false): Message
    {
        $uploadedAttachments = [];

        foreach ($data->allAttachments() as $file) {
            if ($file->isValid()) {
                $path = $file->store('messages/attachments', 'public');
                $uploadedAttachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType() ?: $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        $first = $uploadedAttachments[0] ?? null;

        $attributes = [
            'name' => $data->name,
            'email' => $data->email,
            'subject' => $data->subject,
            'body' => $data->body,
            'attachment_path' => $first['path'] ?? null,
            'attachment_name' => $first['name'] ?? null,
            'attachment_mime' => $first['mime'] ?? null,
            'attachment_size' => $first['size'] ?? null,
            'status' => MessageStatus::New,
            'ip_address' => $data->ipAddress,
        ];

        if (self::$hasAttachmentsColumn === null) {
            self::$hasAttachmentsColumn = Schema::hasColumn('messages', 'attachments');
        }

        if (self::$hasAttachmentsColumn) {
            $attributes['attachments'] = ! empty($uploadedAttachments) ? $uploadedAttachments : null;
        }

        $message = Message::create($attributes);

        // Selalu buat notifikasi in-app ke panel admin secara instan tanpa menunggu antrean
        $this->notificationService->notifyAdminsInApp($message);

        if ($syncNotifications || app()->runningUnitTests()) {
            $this->notificationService->notifyReceiver($message);
            $this->notificationService->notifySender($message);
        } else {
            SendMessageNotificationsJob::dispatchAfterResponse($message);
        }

        return $message;
    }
}
