<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Pages;

use App\Enums\MessageStatus;
use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Messages\MessageResource;
use App\Mail\MessageReplyMail;
use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EditMessage extends EditRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = MessageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Message $record */
        $record = $this->getRecord();

        if ($record->status === MessageStatus::New) {
            $record->update([
                'status' => MessageStatus::Read,
                'read_at' => now(),
            ]);

            $data['status'] = MessageStatus::Read->value;
            $data['read_at'] = now()->toDateTimeString();
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAsRead')
                ->label('Tandai Telah Dibaca')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->visible(fn (Message $record): bool => $record->status === MessageStatus::New)
                ->action(function (Message $record): void {
                    $record->update([
                        'status' => MessageStatus::Read,
                        'read_at' => now(),
                    ]);

                    $this->fillForm();

                    Notification::make()
                        ->title('Pesan ditandai telah dibaca')
                        ->success()
                        ->send();
                }),
            Action::make('markAsUnread')
                ->label('Tandai Belum Dibaca')
                ->icon('heroicon-m-envelope')
                ->color('gray')
                ->visible(fn (Message $record): bool => $record->status === MessageStatus::Read)
                ->action(function (Message $record): void {
                    $record->update([
                        'status' => MessageStatus::New,
                        'read_at' => null,
                    ]);

                    $this->fillForm();

                    Notification::make()
                        ->title('Pesan ditandai belum dibaca')
                        ->info()
                        ->send();
                }),
            Action::make('reply')
                ->label('Balas Email')
                ->icon('heroicon-m-arrow-uturn-left')
                ->color('success')
                ->modalHeading(fn (Message $record): string => 'Balas Pesan: '.$record->name)
                ->modalDescription(fn (Message $record): string => 'Kirim email balasan resmi langsung ke alamat '.$record->email)
                ->modalSubmitActionLabel('Kirim Balasan')
                ->schema([
                    TextInput::make('recipient')
                        ->label('Penerima Email')
                        ->default(fn (Message $record): string => "{$record->name} <{$record->email}>")
                        ->disabled(),
                    TextInput::make('subject')
                        ->label('Subjek Email')
                        ->default(fn (Message $record): string => str_starts_with($record->subject, 'Re: ') ? $record->subject : 'Re: '.$record->subject)
                        ->required(),
                    Textarea::make('reply_message')
                        ->label('Isi Balasan')
                        ->rows(6)
                        ->placeholder('Tuliskan respon atau balasan Anda di sini...')
                        ->required(),
                ])
                ->action(function (array $data, Message $record): void {
                    try {
                        Mail::to($record->email)->send(new MessageReplyMail(
                            messageRecord: $record,
                            replyBody: $data['reply_message'],
                            replySubject: $data['subject']
                        ));

                        $record->update([
                            'status' => MessageStatus::Replied,
                        ]);

                        Notification::make()
                            ->title('Email balasan berhasil dikirim')
                            ->body('Pesan balasan telah dikirimkan ke '.$record->email)
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Log::error('Gagal mengirim balasan email: '.$e->getMessage(), [
                            'message_id' => $record->id,
                            'exception' => $e,
                        ]);

                        Notification::make()
                            ->title('Gagal mengirim email balasan')
                            ->body('Terjadi kendala saat mengirim email: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            DeleteAction::make(),
        ];
    }
}
