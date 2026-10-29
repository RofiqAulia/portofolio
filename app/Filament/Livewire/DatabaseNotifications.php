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
        if ($notification && isset($notification->data['actions'][0]['url'])) {
            $url = (string) $notification->data['actions'][0]['url'];
            if (preg_match('#/admin/messages/(\d+)/edit#', $url, $matches)) {
                Message::where('id', (int) $matches[1])
                    ->where('status', MessageStatus::New)
                    ->update([
                        'status' => MessageStatus::Read,
                        'read_at' => now(),
                    ]);
            }
        }
    }

    #[On('markedNotificationAsUnread')]
    public function markNotificationAsUnread(string $id): void
    {
        parent::markNotificationAsUnread($id);

        $notification = $this->getNotificationsQuery()->where('id', $id)->first();
        if ($notification && isset($notification->data['actions'][0]['url'])) {
            $url = (string) $notification->data['actions'][0]['url'];
            if (preg_match('#/admin/messages/(\d+)/edit#', $url, $matches)) {
                Message::where('id', (int) $matches[1])
                    ->where('status', MessageStatus::Read)
                    ->update([
                        'status' => MessageStatus::New,
                        'read_at' => null,
                    ]);
            }
        }
    }

    protected function syncMessages(): void
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            app(MessageNotificationService::class)->syncUnreadInAppNotificationsForUser($user);
        }
    }
}
