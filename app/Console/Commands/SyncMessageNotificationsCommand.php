<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MessageNotificationService;
use Illuminate\Console\Command;

class SyncMessageNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:sync-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronkan notifikasi in-app untuk seluruh pesan yang belum terbaca ke panel admin';

    /**
     * Execute the console command.
     */
    public function handle(MessageNotificationService $notificationService): int
    {
        $this->info('Menyinkronkan notifikasi pesan belum dibaca...');

        $synced = $notificationService->syncUnreadInAppNotifications();

        $this->info("Berhasil memproses {$synced} pesan belum dibaca.");

        return self::SUCCESS;
    }
}
