<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\MessageData;
use App\Enums\MessageStatus;
use App\Models\Message;

final class CreateMessage
{
    public function execute(MessageData $data): Message
    {
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;
        $attachmentSize = null;

        if ($data->attachment !== null && $data->attachment->isValid()) {
            $attachmentPath = $data->attachment->store('messages/attachments', 'public');
            $attachmentName = $data->attachment->getClientOriginalName();
            $attachmentMime = $data->attachment->getClientMimeType() ?: $data->attachment->getMimeType();
            $attachmentSize = $data->attachment->getSize();
        }

        return Message::create([
            'name' => $data->name,
            'email' => $data->email,
            'subject' => $data->subject,
            'body' => $data->body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_mime' => $attachmentMime,
            'attachment_size' => $attachmentSize,
            'status' => MessageStatus::New,
            'ip_address' => $data->ipAddress,
        ]);
    }
}
