<?php

declare(strict_types=1);

namespace App\Filament\Resources\SkillCategories\Tables;

use App\Enums\SkillType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class SkillCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof SkillType ? $state->label() : ($state ?? ''))
                    ->colors([
                        'primary' => SkillType::Hard->value,
                        'success' => SkillType::Soft->value,
                        'warning' => SkillType::Analysis->value,
                    ]),
                TextColumn::make('skills_count')
                    ->label('Jumlah Skill')
                    ->counts('skills'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
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
