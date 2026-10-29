<?php

declare(strict_types=1);

namespace App\Filament\Livewire;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageNotificationService;
use Filament\Livewire\DatabaseNotifications as BasePanelDatabaseNotifications;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\On;

class DatabaseNotifications extends BasePanelDatabaseNotifications
{
    protected bool $hasSynced = false;

    public function mount(): void
    {
        $this->syncMessages();
    }

    public function getUnreadNotificationsCount(): int
    {
        $this->syncMessages();

        return $this->getUnreadNotificationsQuery()->count();
    }

    public function getUnreadNotificationsQuery(): Builder|Relation
    {
        return $this->getNotificationsQuery()->whereNull('read_at');
    }

    public function getNotificationsQuery(): Builder|Relation
    {
        $this->syncMessages();

        $user = $this->getUser();

        if (! $user) {
            abort(401);
        }

        return $user->notifications();
    }

    public function markAllNotificationsAsRead(): void
    {
        parent::markAllNotificationsAsRead();

        Message::where('status', MessageStatus::New)->update([
            'status' => MessageStatus::Read,
            'read_at' => now(),
        ]);
    }

    #[On('markedNotificationAsRead')]
    public function markNotificationAsRead(string $id): void
    {
        parent::markNotificationAsRead($id);

        $notification = $this->getNotificationsQuery()->where('id', $id)->first();
        $messageId = $this->extractMessageIdFromNotification($notification);

        if ($messageId !== null) {
            Message::where('id', $messageId)
                ->where('status', MessageStatus::New)
                ->update([
                    'status' => MessageStatus::Read,
                    'read_at' => now(),
                ]);
        }
    }

    #[On('markedNotificationAsUnread')]
    public function markNotificationAsUnread(string $id): void
    {
        parent::markNotificationAsUnread($id);

        $notification = $this->getNotificationsQuery()->where('id', $id)->first();
        $messageId = $this->extractMessageIdFromNotification($notification);

        if ($messageId !== null) {
            Message::where('id', $messageId)
                ->where('status', MessageStatus::Read)
                ->update([
                    'status' => MessageStatus::New,
                    'read_at' => null,
                ]);
        }
    }

    protected function extractMessageIdFromNotification(mixed $notification): ?int
    {
        if (! $notification || empty($notification->data) || ! is_array($notification->data)) {
            return null;
        }

        $data = $notification->data;

        // 1. Cek langsung dari viewData
        if (isset($data['viewData']['message_id']) && is_numeric($data['viewData']['message_id'])) {
            return (int) $data['viewData']['message_id'];
        }

        // 2. Cek dari actions
        if (! empty($data['actions']) && is_array($data['actions'])) {
            foreach ($data['actions'] as $action) {
                if (isset($action['name']) && is_string($action['name'])) {
                    if (str_starts_with($action['name'], 'view_message_')) {
                        $idStr = substr($action['name'], strlen('view_message_'));
                        if (is_numeric($idStr)) {
                            return (int) $idStr;
                        }
                    }
                    if (str_starts_with($action['name'], 'mark_read_')) {
                        $idStr = substr($action['name'], strlen('mark_read_'));
                        if (is_numeric($idStr)) {
                            return (int) $idStr;
                        }
                    }
                }

                if (isset($action['url']) && is_string($action['url'])) {
                    if (preg_match('#/admin/messages/(\d+)/edit#', $action['url'], $matches)) {
                        return (int) $matches[1];
                    }
                }
            }
        }

        return null;
    }

    protected function syncMessages(): void
    {
        if ($this->hasSynced) {
            return;
        }

        $user = $this->getUser();

        if ($user instanceof User) {
            app(MessageNotificationService::class)->syncUnreadInAppNotificationsForUser($user);
            $this->hasSynced = true;
        }
    }
}
