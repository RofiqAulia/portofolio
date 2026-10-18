<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documentations\Schemas;

use App\Enums\DocumentationType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class DocumentationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dokumen & Blueprint')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Dokumentasi / Blueprint')
                            ->placeholder('Contoh: Desain ERD & Skema Database Smart Warehouse')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'ERD & Database' => 'ERD & Database',
                                'UML & Flowchart' => 'UML & Flowchart',
                                'Arsitektur Sistem' => 'Arsitektur Sistem',
                                'Software Design Pattern' => 'Software Design Pattern',
                                'Dokumentasi API & SRS' => 'Dokumentasi API & SRS',
                                'Panduan & SOP' => 'Panduan & SOP',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->default('ERD & Database')
                            ->required()
                            ->searchable(),
                        Select::make('project_id')
                            ->label('Terkait Proyek (Opsional)')
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Textarea::make('summary')
                            ->label('Ringkasan Singkat')
                            ->placeholder('Ringkasan tentang apa yang dimuat dalam diagram/dokumen ini...')
                            ->rows(3)
                            ->maxLength(500),
                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap / Catatan Arsitektur')
                            ->placeholder('Jelaskan detail keputusan arsitektur, normalisasi, atau alur kerja...')
                            ->nullable(),
                    ]),

                Section::make('Berkas & Media Dokumentasi')
                    ->schema([
                        Select::make('type')
                            ->label('Tipe Artefak')
                            ->options([
                                DocumentationType::Image->value => 'Diagram / Gambar (PNG, JPG, SVG, WebP)',
                                DocumentationType::Document->value => 'Dokumen / Berkas (PDF, DOCX, ZIP, XLS)',
                                DocumentationType::Link->value => 'Tautan Eksternal (Figma, Miro, Drive, Whimsical)',
                            ])
                            ->default(DocumentationType::Image->value)
                            ->required()
                            ->live(),
                        FileUpload::make('file_path')
                            ->label('Unggah Berkas / Diagram')
                            ->disk('public')
                            ->directory('documentations/files')
                            ->visibility('public')
                            ->maxSize(25600)
                            ->acceptedFileTypes([
                                'image/jpeg', 'image/png', 'image/webp', 'image/svg+xml',
                                'application/pdf', 'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/zip', 'application/x-zip-compressed',
                            ])
                            ->nullable(),
                        FileUpload::make('preview_image_path')
                            ->label('Gambar Sampul / Preview (Opsional)')
                            ->helperText('Disarankan jika berkas berupa PDF/dokumen agar memiliki tampilan visual kartu yang menarik.')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('documentations/previews')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->nullable(),
                        TextInput::make('external_url')
                            ->label('Tautan Luar (Figma / Miro / Whimsical / GitHub / Drive)')
                            ->url()
                            ->placeholder('https://www.figma.com/file/...')
                            ->nullable(),
                    ])->columns(2),

                Section::make('Status & Publikasi')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Publikasikan Dokumen')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Tandai sebagai Unggulan (Featured)')
                            ->default(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('published_at')
                            ->label('Waktu Publikasi')
                            ->default(now()),
                    ])->columns(2),
            ]);
    }
}
