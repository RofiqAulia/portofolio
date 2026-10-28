<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\UserRole;
use App\Filament\Resources\Messages\MessageResource;
use App\Mail\MessageAutoReplyMail;
use App\Mail\NewMessageNotificationMail;
use App\Models\Message;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MessageNotificationService
{
    /**
     * Kirim notifikasi lengkap:
     * 1. Email ke penerima (admin: mrofiqaulia@gmail.com)
     * 2. Email auto-reply konfirmasi ke pengirim pesan ($message->email)
     * 3. Notifikasi real-time in-app di dashboard admin Filament
     */
    public function sendNewMessageNotifications(Message $message): void
    {
        $this->notifyReceiver($message);
        $this->notifySender($message);
        $this->notifyAdminsInApp($message);
    }

    public function notifyReceiver(Message $message): void
    {
        try {
            $adminEmail = SiteSetting::get('contact_email') ?: config('mail.from.address', 'mrofiqaulia@gmail.com');

            $recipients = array_unique(array_filter([
                'mrofiqaulia@gmail.com',
                $adminEmail,
            ]));

            foreach ($recipients as $recipient) {
                Mail::to($recipient)->send(new NewMessageNotificationMail($message));
            }
        } catch (Throwable $e) {
            Log::error('Gagal mengirim notifikasi email pesan baru ke penerima (admin): '.$e->getMessage(), [
                'message_id' => $message->id,
                'exception' => $e,
            ]);
        }
    }

    public function notifySender(Message $message): void
    {
        try {
            if (filter_var($message->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($message->email)->send(new MessageAutoReplyMail($message));
            }
        } catch (Throwable $e) {
            Log::error('Gagal mengirim email konfirmasi/auto-reply ke pengirim pesan: '.$e->getMessage(), [
                'message_id' => $message->id,
                'recipient_email' => $message->email,
                'exception' => $e,
            ]);
        }
    }

    public function notifyAdminsInApp(Message $message): void
    {
        try {
            $admins = User::where('role', UserRole::Admin)->get();

            if ($admins->isNotEmpty()) {
                FilamentNotification::make()
                    ->title('Pesan Kontak Baru Masuk')
                    ->body("{$message->name}: {$message->subject}")
                    ->icon('heroicon-o-envelope')
                    ->iconColor('primary')
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->label('Buka Pesan')
                            ->url(MessageResource::getUrl('edit', ['record' => $message])),
                    ])
                    ->sendToDatabase($admins);
            }
        } catch (Throwable $e) {
            Log::error('Gagal mengirim notifikasi in-app ke admin: '.$e->getMessage(), [
                'message_id' => $message->id,
                'exception' => $e,
            ]);
        }
    }
}
