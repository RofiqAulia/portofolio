<?php

declare(strict_types=1);

namespace App\Filament\Resources\Experiences\Schemas;

use App\Enums\ExperienceType;
use App\Enums\MediaType;
use App\Enums\ProjectStatus;
use Filament\Forms\Components\DatePicker;
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

final class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        Select::make('type')
                            ->label('Tipe Pengalaman')
                            ->options([
                                ExperienceType::Internship->value => ExperienceType::Internship->label(),
                                ExperienceType::Organization->value => ExperienceType::Organization->label(),
                            ])
                            ->required(),
                        TextInput::make('position')
                            ->label('Posisi / Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                                if ($operation === 'create' || empty($get('slug'))) {
                                    $org = (string) ($get('organization_name') ?? '');
                                    $combined = trim(($state ?? '').' '.$org);
                                    if (! empty($combined)) {
                                        $set('slug', Str::slug($combined));
                                    }
                                }
                            }),
                        TextInput::make('organization_name')
                            ->label('Nama Lembaga / Perusahaan / Organisasi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                                if ($operation === 'create' || empty($get('slug'))) {
                                    $pos = (string) ($get('position') ?? '');
                                    $combined = trim($pos.' '.($state ?? ''));
                                    if (! empty($combined)) {
                                        $set('slug', Str::slug($combined));
                                    }
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->helperText('URL slug untuk tautan detail pengalaman (otomatis disinkronkan).')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->disabled(fn ($get) => (bool) $get('is_current')),
                        Toggle::make('is_current')
                            ->label('Saat Ini Masih Menjabat / Aktif')
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('end_date', null) : null),
                    ])->columns(2),

                Section::make('Detail & Cerita')
                    ->schema([
                        Textarea::make('summary')
                            ->label('Ringkasan Singkat')
                            ->rows(3)
                            ->maxLength(300),
                        RichEditor::make('story')
                            ->label('Cerita Pengalaman Lengkap')
                            ->nullable(),
                        Select::make('technologies')
                            ->label('Teknologi / Tools Terkait')
                            ->relationship('technologies', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        FileUpload::make('cover_path')
                            ->label('Foto Sampul / Logo')
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
                            ->directory('experiences/covers')
                            ->disk('public')
                            ->visibility('public'),
                    ]),

                Section::make('Sorotan Pencapaian (Highlights)')
                    ->schema([
                        Repeater::make('highlights')
                            ->label('Daftar Pencapaian Utama')
                            ->relationship('highlights')
                            ->schema([
                                TextInput::make('content')
                                    ->label('Pencapaian / Tanggung Jawab Utama')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->defaultItems(1),
                    ]),

                Section::make('Galeri Media Tambahan')
                    ->schema([
                        Repeater::make('media')
                            ->label('Foto / Dokumentasi Kegiatan')
                            ->relationship('media')
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Berkas Media')
                                    ->image()
                                    ->directory('experiences/gallery')
                                    ->disk('public')
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

                Section::make('Status & Urutan')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                ProjectStatus::Draft->value => ProjectStatus::Draft->label(),
                                ProjectStatus::Published->value => ProjectStatus::Published->label(),
                            ])
                            ->default(ProjectStatus::Draft->value)
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }
}
