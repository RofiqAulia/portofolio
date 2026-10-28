<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Message;
use App\Services\MessageNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public Message $message,
    ) {}

    public function handle(MessageNotificationService $notificationService): void
    {
        $notificationService->sendNewMessageNotifications($this->message);
    }
}
