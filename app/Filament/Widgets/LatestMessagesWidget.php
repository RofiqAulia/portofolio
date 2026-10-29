<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\MessageStatus;
use App\Filament\Resources\Messages\MessageResource;
use App\Models\Message;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestMessagesWidget extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Pesan Masuk Terbaru';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Message::query()->latest()->limit(5))
            ->description('5 pesan terbaru dari formulir kontak pengunjung')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Pengirim')
                    ->description(fn (Message $record) => $record->email)
                    ->limit(25),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(30),
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
                    ->label('Waktu')
                    ->since(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Message $record): string => MessageResource::getUrl('edit', ['record' => $record])),
            ])
            ->headerActions([
                Action::make('view_all')
                    ->label('Lihat Semua')
                    ->icon('heroicon-m-arrow-right')
                    ->url(MessageResource::getUrl('index')),
            ])
            ->emptyStateHeading('Belum ada pesan masuk')
            ->emptyStateDescription('Pesan dari pengunjung form kontak akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-envelope');
    }
}
