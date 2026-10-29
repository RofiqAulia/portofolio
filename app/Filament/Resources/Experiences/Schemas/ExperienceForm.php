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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Informasi Jabatan & Lembaga')
                        ->description('Peran, instansi penyelenggara, lokasi, dan periode masa bakti')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                Select::make('type')
                                    ->label('Tipe Pengalaman')
                                    ->options([
                                        ExperienceType::Internship->value => ExperienceType::Internship->label(),
                                        ExperienceType::Organization->value => ExperienceType::Organization->label(),
                                    ])
                                    ->required(),

                                TextInput::make('position')
                                    ->label('Posisi / Jabatan')
                                    ->placeholder('Contoh: Backend Engineer Intern atau Ketua Divisi R&D')
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
                            ]),

                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('organization_name')
                                    ->label('Nama Lembaga / Instansi / Perusahaan')
                                    ->placeholder('Contoh: PT Telekomunikasi Indonesia')
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
                                    ->helperText('Otomatis disinkronkan dari posisi dan lembaga')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),

                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('location')
                                    ->label('Lokasi')
                                    ->placeholder('Contoh: Jakarta Selatan / Hybrid')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->maxLength(255),

                                Toggle::make('is_current')
                                    ->label('Masih Aktif / Menjabat')
                                    ->helperText('Centang jika saat ini masih berlangsung')
                                    ->live()
                                    ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('end_date', null) : null),
                            ]),

                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->required(),

                                DatePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                                    ->prefixIcon('heroicon-o-calendar-days')
                                    ->disabled(fn ($get) => (bool) $get('is_current')),
                            ]),
                        ]),

                    Section::make('Cerita Pengalaman & Teknologi')
                        ->description('Rincian tanggung jawab, keahlian yang diterapkan, dan narasi pengalaman')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Select::make('technologies')
                                ->label('Teknologi / Tools Terkait')
                                ->relationship('technologies', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable(),

                            Textarea::make('summary')
                                ->label('Ringkasan Singkat')
                                ->placeholder('Ringkasan 1-2 kalimat tentang peran dan capaian utama...')
                                ->rows(3)
                                ->maxLength(300),

                            RichEditor::make('story')
                                ->label('Cerita Pengalaman Lengkap')
                                ->placeholder('Deskripsikan proyek nyata yang dikerjakan, kendala yang dihadapi, kepemimpinan, dan solusi...')
                                ->nullable(),
                        ]),

                    Section::make('Sorotan Pencapaian (Key Highlights)')
                        ->description('Daftar poin prestasi, target tercapai, atau output terukur')
                        ->icon('heroicon-o-sparkles')
                        ->collapsible()
                        ->schema([
                            Repeater::make('highlights')
                                ->label('Daftar Pencapaian')
                                ->relationship('highlights')
                                ->schema([
                                    Grid::make(['default' => 1, 'md' => 12])->schema([
                                        TextInput::make('content')
                                            ->label('Uraian Pencapaian / Tanggung Jawab')
                                            ->placeholder('Contoh: Mengembangkan 5 modul backend microservices dengan arsitektur REST...')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(['default' => 1, 'md' => 9]),

                                        TextInput::make('sort_order')
                                            ->label('Urutan')
                                            ->numeric()
                                            ->default(0)
                                            ->columnSpan(['default' => 1, 'md' => 3]),
                                    ]),
                                ])
                                ->itemLabel(fn (array $state): ?string => $state['content'] ?? null)
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->collapseAllAction(fn ($action) => $action->label('Ciutkan Semua'))
                                ->expandAllAction(fn ($action) => $action->label('Bentangkan Semua'))
                                ->addActionLabel('+ Tambah Poin Pencapaian')
                                ->defaultItems(1),
                        ]),

                    Section::make('Galeri Media Tambahan')
                        ->description('Foto kegiatan, suasana kerja, atau dokumentasi keterlibatan')
                        ->icon('heroicon-o-photo')
                        ->collapsible()
                        ->schema([
                            Repeater::make('media')
                                ->label('Daftar Media Kegiatan')
                                ->relationship('media')
                                ->schema([
                                    Grid::make(['default' => 1, 'md' => 12])->schema([
                                        FileUpload::make('path')
                                            ->label('Berkas Media')
                                            ->image()
                                            ->directory('experiences/gallery')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->required()
                                            ->columnSpan(['default' => 1, 'md' => 7]),

                                        Grid::make(1)->schema([
                                            Select::make('type')
                                                ->label('Tipe')
                                                ->options([
                                                    MediaType::Image->value => 'Gambar / Foto',
                                                    MediaType::Video->value => 'Video',
                                                ])
                                                ->default(MediaType::Image->value),

                                            TextInput::make('caption')
                                                ->label('Keterangan / Caption')
                                                ->placeholder('Contoh: Presentasi akhir sprint tim')
                                                ->maxLength(255),

                                            TextInput::make('sort_order')
                                                ->label('Urutan')
                                                ->numeric()
                                                ->default(0),
                                        ])->columnSpan(['default' => 1, 'md' => 5]),
                                    ]),
                                ])
                                ->itemLabel(fn (array $state): ?string => ($state['caption'] ?? null) ?: 'Dokumentasi #'.($state['sort_order'] ?? '0'))
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->collapseAllAction(fn ($action) => $action->label('Ciutkan Semua'))
                                ->expandAllAction(fn ($action) => $action->label('Bentangkan Semua'))
                                ->addActionLabel('+ Tambah Dokumentasi Foto')
                                ->defaultItems(0),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 2]),

                Group::make([
                    Section::make('Status & Urutan Tampil')
                        ->description('Visibilitas di timeline portofolio')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Select::make('status')
                                ->label('Status Publikasi')
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
                        ]),

                    Section::make('Foto Sampul / Logo Instansi')
                        ->description('Logo resmi organisasi atau foto tempat kegiatan')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
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
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
