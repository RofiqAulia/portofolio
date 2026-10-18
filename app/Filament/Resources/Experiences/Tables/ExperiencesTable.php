<?php

declare(strict_types=1);

namespace App\Filament\Resources\Experiences\Tables;

use App\Enums\ExperienceType;
use App\Enums\ProjectStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ExperiencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_path')
                    ->label('Sampul')
                    ->disk('public')
                    ->square(),
                TextColumn::make('position')
                    ->label('Posisi / Jabatan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->organization_name),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => ExperienceType::Internship->value,
                        'success' => ExperienceType::Organization->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof ExperienceType ? $state->label() : ($state ?? '')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => ProjectStatus::Draft->value,
                        'success' => ProjectStatus::Published->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof ProjectStatus ? $state->label() : ($state ?? '')),
                TextColumn::make('start_date')
                    ->label('Periode')
                    ->formatStateUsing(function ($record) {
                        $start = $record->start_date?->format('M Y');
                        $end = $record->is_current ? 'Sekarang' : ($record->end_date ? $record->end_date->format('M Y') : '-');

                        return "{$start} - {$end}";
                    }),
                TextColumn::make('attachments_count')
                    ->label('Lampiran')
                    ->counts('attachments'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('type')
                    ->label('Filter Tipe Pengalaman')
                    ->options([
                        ExperienceType::Internship->value => ExperienceType::Internship->label(),
                        ExperienceType::Organization->value => ExperienceType::Organization->label(),
                    ]),
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        ProjectStatus::Draft->value => ProjectStatus::Draft->label(),
                        ProjectStatus::Published->value => ProjectStatus::Published->label(),
                    ]),
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
