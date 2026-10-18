<?php

declare(strict_types=1);

namespace App\Filament\Resources\Profiles\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->headline),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('phone')
                    ->label('Telepon'),
                IconColumn::make('is_open_to_work')
                    ->label('Open To Work')
                    ->boolean(),
                TextColumn::make('social_links_count')
                    ->label('Tautan Sosial')
                    ->counts('socialLinks'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
