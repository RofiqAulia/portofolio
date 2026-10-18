<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Tables;

use App\Enums\MessageStatus;
use App\Models\Message;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
