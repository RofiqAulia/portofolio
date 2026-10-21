<?php

declare(strict_types=1);

namespace App\Filament\Resources\Certifications\Tables;

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

final class CertificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Logo / Foto')
                    ->disk('public')
                    ->getStateUsing(fn ($record) => $record->logo_path ?: $record->getFirstImage())
                    ->circular(),

                TextColumn::make('title')
                    ->label('Judul Penghargaan / Sertifikasi')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => trim(($record->issuer ?? '').($record->issued_year ? ' • '.$record->issued_year : '')) ?: null),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),

                TextColumn::make('media_summary')
                    ->label('Lampiran')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(function ($record) {
                        $imgCount = $record->getImageCount();
                        $fileCount = $record->getFileCount();

                        if ($imgCount === 0 && $fileCount === 0) {
                            return 'Tanpa Berkas';
                        }

                        $parts = [];
                        if ($imgCount > 0) {
                            $parts[] = "{$imgCount} Foto";
                        }
                        if ($fileCount > 0) {
                            $parts[] = "{$fileCount} File";
                        }

                        return implode(', ', $parts);
                    }),

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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Hak Cipta (HKI)' => 'Hak Cipta (HKI)',
                        'Sertifikasi Magang' => 'Sertifikasi Magang',
                        'Penghargaan Kinerja' => 'Penghargaan Kinerja',
                        'Kepemimpinan' => 'Kepemimpinan',
                        'Predikat Akademik' => 'Predikat Akademik',
                        'Sertifikasi Profesi' => 'Sertifikasi Profesi',
                        'Pelatihan / Workshop' => 'Pelatihan / Workshop',
                        'Blueprint Kredensial' => 'Blueprint Kredensial',
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
