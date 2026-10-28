<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Tables;

use App\Enums\MessageStatus;
use App\Mail\MessageReplyMail;
use App\Models\Message;
use Filament\Actions\Action;
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
                    ->description(fn ($record) => $record->email),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('attachment_name')
                    ->label('Lampiran')
                    ->icon('heroicon-o-paper-clip')
                    ->placeholder('-')
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => MessageStatus::New->value,
                        'info' => MessageStatus::Read->value,
                        'success' => MessageStatus::Replied->value,
                        'gray' => MessageStatus::Archived->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof MessageStatus ? $state->label() : ($state ?? '')),
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
