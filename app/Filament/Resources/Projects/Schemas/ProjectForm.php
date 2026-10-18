<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\MediaType;
use App\Enums\ProjectStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Proyek')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('summary')
                            ->label('Ringkasan Singkat')
                            ->rows(3)
                            ->maxLength(300)
                            ->required(),
                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap / Cerita Proyek')
                            ->nullable(),
                        TextInput::make('role')
                            ->label('Peran dalam Proyek')
                            ->placeholder('Contoh: Full-Stack Developer, UI/UX Designer')
                            ->maxLength(255),
                        Select::make('technologies')
                            ->label('Teknologi yang Digunakan')
                            ->relationship('technologies', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),

                Section::make('Media & Thumbnail')
                    ->schema([
                        FileUpload::make('thumbnail_path')
                            ->label('Thumbnail Utama')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('1200')
                            ->imageResizeUpscale(false)
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('projects/thumbnails')
                            ->visibility('public')
                            ->nullable(),
                        Repeater::make('media')
                            ->label('Galeri Media Proyek')
                            ->relationship('media')
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Berkas Media')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        null,
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('1600')
                                    ->imageResizeTargetHeight('1600')
                                    ->imageResizeUpscale(false)
                                    ->maxSize(10240)
                                    ->disk('public')
                                    ->directory('projects/gallery')
                                    ->visibility('public')
                                    ->required(),
                                TextInput::make('caption')
                                    ->label('Keterangan / Caption')
                                    ->maxLength(255),
                                Select::make('type')
                                    ->label('Tipe')
                                    ->options([
                                        MediaType::Image->value => 'Gambar',
                                        MediaType::Video->value => 'Video',
                                    ])
                                    ->default(MediaType::Image->value),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->defaultItems(0),
                    ]),

                Section::make('Tautan & Linimasa')
                    ->schema([
                        TextInput::make('demo_url')
                            ->label('URL Live Demo')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('repo_url')
                            ->label('URL Repositori / Source Code')
                            ->url()
                            ->maxLength(255),
                        DatePicker::make('started_at')
                            ->label('Tanggal Mulai'),
                        DatePicker::make('finished_at')
                            ->label('Tanggal Selesai'),
                    ])->columns(2),

                Section::make('Publikasi & Tampilan')
                    ->schema([
                        Select::make('status')
                            ->label('Status Proyek')
                            ->options([
                                ProjectStatus::Draft->value => ProjectStatus::Draft->label(),
                                ProjectStatus::Published->value => ProjectStatus::Published->label(),
                            ])
                            ->default(ProjectStatus::Draft->value)
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Jadikan Proyek Unggulan (Featured)')
                            ->default(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('published_at')
                            ->label('Waktu Terbit')
                            ->default(now()),
                    ])->columns(2),
            ]);
    }
}
