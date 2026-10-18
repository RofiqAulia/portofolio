<?php

declare(strict_types=1);

namespace App\Filament\Resources\SkillCategories\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    protected static ?string $title = 'Daftar Skill';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Skill')
                    ->required()
                    ->maxLength(255),
                TextInput::make('level')
                    ->label('Tingkat Kemahiran (0 - 100)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->nullable()
                    ->helperText('Hanya wajib diisi untuk kategori Hard Skill'),
                TextInput::make('level_label')
                    ->label('Label Tingkat (Contoh: Expert, Advanced)')
                    ->maxLength(100)
                    ->nullable(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_visible')
                    ->label('Tampilkan di Landing Page')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Skill')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level')
                    ->label('Persentase')
                    ->suffix('%')
                    ->placeholder('-'),
                TextColumn::make('level_label')
                    ->label('Label')
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Tampil')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Skill'),
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
