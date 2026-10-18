<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documentations\Tables;

use App\Enums\DocumentationType;
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

final class DocumentationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_image_path')
                    ->label('Sampul')
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => $record->isImage() && $record->file_path ? asset('storage/'.$record->file_path) : null)
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul Dokumentasi')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->project?->title ? 'Proyek: '.$record->project->title : null),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => DocumentationType::Image->value,
                        'warning' => DocumentationType::Document->value,
                        'info' => DocumentationType::Link->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof DocumentationType ? $state->label() : ($state ?? '')),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Waktu Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'ERD & Database' => 'ERD & Database',
                        'UML & Flowchart' => 'UML & Flowchart',
                        'Arsitektur Sistem' => 'Arsitektur Sistem',
                        'Software Design Pattern' => 'Software Design Pattern',
                        'Dokumentasi API & SRS' => 'Dokumentasi API & SRS',
                        'Panduan & SOP' => 'Panduan & SOP',
                    ]),
                SelectFilter::make('type')
                    ->label('Tipe Artefak')
                    ->options([
                        DocumentationType::Image->value => 'Diagram / Gambar',
                        DocumentationType::Document->value => 'Dokumen / PDF',
                        DocumentationType::Link->value => 'Tautan Eksternal',
                    ]),
                TernaryFilter::make('is_published')
                    ->label('Status Publikasi'),
                TernaryFilter::make('is_featured')
                    ->label('Hanya Unggulan'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
