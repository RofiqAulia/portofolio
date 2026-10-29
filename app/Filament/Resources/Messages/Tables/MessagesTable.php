<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Tables;

use App\Enums\MessageStatus;
use App\Mail\MessageReplyMail;
use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengirim')
                    ->searchable()
                    ->sortable()
                    ->weight(fn (Message $record): string => $record->status === MessageStatus::New ? 'bold' : 'normal')
                    ->description(fn ($record) => $record->email),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(40)
                    ->weight(fn (Message $record): string => $record->status === MessageStatus::New ? 'bold' : 'normal'),
                TextColumn::make('attachments_info')
                    ->label('Lampiran')
                    ->state(function (Message $record): ?string {
                        $attachments = $record->getAttachments();
                        $count = count($attachments);

                        if ($count === 0) {
                            return null;
                        }

                        if ($count === 1) {
                            return $attachments[0]['name'] ?? '1 berkas';
                        }

                        return "{$count} berkas";
                    })
                    ->icon(fn ($state) => $state ? 'heroicon-o-paper-clip' : null)
                    ->badge(fn (Message $record) => $record->hasMultipleAttachments())
                    ->color('info')
                    ->placeholder('-')
                    ->limit(25)
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => MessageStatus::New->value,
                        'success' => MessageStatus::Read->value,
                        'info' => MessageStatus::Replied->value,
                        'secondary' => MessageStatus::Archived->value,
                    ])
                    ->icons([
                        'heroicon-m-envelope' => MessageStatus::New->value,
                        'heroicon-m-check-circle' => MessageStatus::Read->value,
                        'heroicon-m-arrow-uturn-left' => MessageStatus::Replied->value,
                        'heroicon-m-archive-box' => MessageStatus::Archived->value,
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        MessageStatus::New => 'Belum Dibaca',
                        MessageStatus::Read => 'Sudah Dibaca',
                        MessageStatus::Replied => 'Dibalas',
                        MessageStatus::Archived => 'Diarsipkan',
                        default => $state instanceof MessageStatus ? $state->label() : (string) $state,
                    }),
                TextColumn::make('read_at')
                    ->label('Dibaca Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum dibaca')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        MessageStatus::New->value => MessageStatus::New->label(),
                        MessageStatus::Read->value => MessageStatus::Read->label(),
                        MessageStatus::Replied->value => MessageStatus::Replied->label(),
                        MessageStatus::Archived->value => MessageStatus::Archived->label(),
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Detail')
                    ->modalHeading('Detail Pesan Masuk')
                    ->mutateRecordDataUsing(function (array $data, Message $record): array {
                        if ($record->status === MessageStatus::New) {
                            $record->update([
                                'status' => MessageStatus::Read,
                                'read_at' => now(),
                            ]);
                            $data['status'] = MessageStatus::Read->value;
                            $data['read_at'] = now();
                        }

                        return $data;
                    }),
                Action::make('markAsRead')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Message $record): bool => $record->status === MessageStatus::New)
                    ->action(function (Message $record): void {
                        $record->update([
                            'status' => MessageStatus::Read,
                            'read_at' => now(),
                        ]);

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
                EditAction::make()
                    ->label('Ubah Status'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Tandai Telah Dibaca')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(function (Collection $records): void {
                            $records->each(function (Message $record): void {
                                if ($record->status === MessageStatus::New) {
                                    $record->update([
                                        'status' => MessageStatus::Read,
                                        'read_at' => now(),
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Pesan terpilih ditandai telah dibaca')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('markAsUnread')
                        ->label('Tandai Belum Dibaca')
                        ->icon('heroicon-m-envelope')
                        ->color('gray')
                        ->action(function (Collection $records): void {
                            $records->each(function (Message $record): void {
                                if ($record->status === MessageStatus::Read) {
                                    $record->update([
                                        'status' => MessageStatus::New,
                                        'read_at' => null,
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Pesan terpilih ditandai belum dibaca')
                                ->info()
                                ->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
