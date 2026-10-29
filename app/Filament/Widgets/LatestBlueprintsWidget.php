<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\DocumentationType;
use App\Filament\Resources\Documentations\DocumentationResource;
use App\Models\Documentation;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestBlueprintsWidget extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected static ?int $sort = 4;

    protected static ?string $heading = 'Dokumentasi & Blueprint Terbaru';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Documentation::query()->with('project')->latest()->limit(5))
            ->description('5 artefak arsitektur sistem dan cetak biru terbaru')
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Blueprint')
                    ->description(fn (Documentation $record) => $record->project?->title ? 'Proyek: '.$record->project->title : null)
                    ->limit(35)
                    ->tooltip(fn (Documentation $record) => $record->title),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->limit(20),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => DocumentationType::Image->value,
                        'warning' => DocumentationType::Document->value,
                        'info' => DocumentationType::Link->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof DocumentationType ? $state->label() : ($state ?? '')),
                TextColumn::make('is_published')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Terbit' : 'Draft')
                    ->colors([
                        'success' => true,
                        'gray' => false,
                    ]),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Buka')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Documentation $record): string => DocumentationResource::getUrl('edit', ['record' => $record])),
            ])
            ->headerActions([
                Action::make('view_all')
                    ->label('Lihat Semua')
                    ->icon('heroicon-m-arrow-right')
                    ->url(DocumentationResource::getUrl('index')),
            ])
            ->emptyStateHeading('Belum ada blueprint atau dokumentasi')
            ->emptyStateDescription('Tambahkan dokumentasi arsitektur atau blueprint untuk proyek Anda.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
