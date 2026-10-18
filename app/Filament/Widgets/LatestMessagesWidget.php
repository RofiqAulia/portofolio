<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\MessageStatus;
use App\Models\Message;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestMessagesWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Pesan Masuk Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Message::query()->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Pengirim')
                    ->description(fn (Message $record) => $record->email),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(40),
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
            ]);
    }
}
