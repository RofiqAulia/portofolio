<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_path')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul Proyek')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->role),
                TextColumn::make('technologies.name')
                    ->label('Teknologi')
                    ->badge()
                    ->color('info')
                    ->separator(','),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => ProjectStatus::Draft->value,
                        'success' => ProjectStatus::Published->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof ProjectStatus ? $state->label() : ($state ?? '')),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Diterbitkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ProjectStatus::Draft->value => ProjectStatus::Draft->label(),
                        ProjectStatus::Published->value => ProjectStatus::Published->label(),
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Unggulan'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
