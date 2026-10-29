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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Informasi Utama')
                        ->description('Rincian judul, peran, ringkasan, dan cerita lengkap proyek')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('title')
                                    ->label('Judul Proyek')
                                    ->placeholder('Contoh: Sistem Informasi Manajemen Rumah Sakit')
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
                                    ->placeholder('sistem-informasi-manajemen-rumah-sakit')
                                    ->helperText('Otomatis dibuat dari judul, unik untuk setiap proyek')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),

                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('role')
                                    ->label('Peran dalam Proyek')
                                    ->placeholder('Contoh: Full-Stack Developer & UI/UX Designer')
                                    ->maxLength(255),

                                Select::make('technologies')
                                    ->label('Teknologi yang Digunakan')
                                    ->relationship('technologies', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                            ]),

                            Textarea::make('summary')
                                ->label('Ringkasan Singkat')
                                ->placeholder('Deskripsi singkat 1-2 kalimat untuk kartu portofolio...')
                                ->rows(3)
                                ->maxLength(300)
                                ->helperText('Maksimal 300 karakter. Ditampilkan pada cuplikan kartu proyek di halaman utama.')
                                ->required(),

                            RichEditor::make('description')
                                ->label('Deskripsi Lengkap / Cerita Proyek')
                                ->placeholder('Jelaskan latar belakang masalah, solusi arsitektur, tantangan teknis, dan hasil capaian proyek...')
                                ->nullable(),
                        ]),

                    Section::make('Galeri Media Proyek')
                        ->description('Kumpulan tangkapan layar antarmuka (UI screenshots) atau video demo proyek')
                        ->icon('heroicon-o-photo')
                        ->collapsible()
                        ->schema([
                            Repeater::make('media')
                                ->label('Daftar Berkas Media')
                                ->relationship('media')
                                ->schema([
                                    Grid::make(['default' => 1, 'md' => 12])->schema([
                                        FileUpload::make('path')
                                            ->label('Unggah Media')
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
                                            ->required()
                                            ->columnSpan(['default' => 1, 'md' => 7]),

                                        Grid::make(1)->schema([
                                            Select::make('type')
                                                ->label('Tipe Media')
                                                ->options([
                                                    MediaType::Image->value => 'Gambar / Foto',
                                                    MediaType::Video->value => 'Video Demo',
                                                ])
                                                ->default(MediaType::Image->value)
                                                ->required(),

                                            TextInput::make('caption')
                                                ->label('Keterangan / Caption')
                                                ->placeholder('Contoh: Tampilan Halaman Dashboard')
                                                ->maxLength(255),

                                            TextInput::make('sort_order')
                                                ->label('Urutan Tampil')
                                                ->numeric()
                                                ->default(0),
                                        ])->columnSpan(['default' => 1, 'md' => 5]),
                                    ]),
                                ])
                                ->itemLabel(fn (array $state): ?string => ($state['caption'] ?? null) ?: 'Media Galeri #'.($state['sort_order'] ?? '0'))
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->collapseAllAction(fn ($action) => $action->label('Ciutkan Semua'))
                                ->expandAllAction(fn ($action) => $action->label('Bentangkan Semua'))
                                ->addActionLabel('+ Tambah Berkas ke Galeri')
                                ->defaultItems(0),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 2]),

                Group::make([
                    Section::make('Publikasi & Tampilan')
                        ->description('Visibilitas dan urutan tayang portofolio')
                        ->icon('heroicon-o-globe-alt')
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
                                ->helperText('Ditampilkan prioritas di Showcase Utama')
                                ->default(false),

                            Grid::make(2)->schema([
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),

                                DateTimePicker::make('published_at')
                                    ->label('Waktu Terbit')
                                    ->default(now()),
                            ]),
                        ]),

                    Section::make('Thumbnail Utama')
                        ->description('Gambar sampul utama kartu proyek')
                        ->icon('heroicon-o-camera')
                        ->schema([
                            FileUpload::make('thumbnail_path')
                                ->label('Sampul Proyek')
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
                        ]),

                    Section::make('Tautan & Linimasa')
                        ->description('Akses link eksternal & periode pengerjaan')
                        ->icon('heroicon-o-link')
                        ->schema([
                            TextInput::make('demo_url')
                                ->label('URL Live Demo')
                                ->prefixIcon('heroicon-o-arrow-top-right-on-square')
                                ->placeholder('https://contoh-proyek.com')
                                ->url()
                                ->maxLength(255),

                            TextInput::make('repo_url')
                                ->label('URL Repositori / GitHub')
                                ->prefixIcon('heroicon-o-code-bracket')
                                ->placeholder('https://github.com/...')
                                ->url()
                                ->maxLength(255),

                            Grid::make(2)->schema([
                                DatePicker::make('started_at')
                                    ->label('Mulai')
                                    ->prefixIcon('heroicon-o-calendar'),

                                DatePicker::make('finished_at')
                                    ->label('Selesai')
                                    ->prefixIcon('heroicon-o-calendar-days'),
                            ]),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
