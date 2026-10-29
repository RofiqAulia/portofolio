<?php

declare(strict_types=1);

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Informasi Penghargaan & Sertifikasi')
                        ->description('Judul kredensial, kategori, penerbit, dan ringkasan capaian')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('title')
                                    ->label('Judul Penghargaan / Sertifikasi')
                                    ->placeholder('Contoh: Hak Cipta Sistem Sovia / Best Performance Engineer')
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
                                    ->helperText('Otomatis disinkronkan dari judul')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),

                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                Select::make('category')
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
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->default('Sertifikasi Magang')
                                    ->required()
                                    ->searchable(),

                                TextInput::make('issuer')
                                    ->label('Lembaga / Instansi Penerbit')
                                    ->placeholder('Contoh: DJKI Kemenkumham RI / FHCI BUMN')
                                    ->maxLength(255)
                                    ->nullable(),
                            ]),

                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('issued_year')
                                    ->label('Tahun / Periode')
                                    ->placeholder('Contoh: 2025, 2025 - 2026, atau IPK 3.68/4.00')
                                    ->maxLength(100)
                                    ->nullable(),

                                TextInput::make('status_label')
                                    ->label('Label Status / Badge')
                                    ->placeholder('Contoh: Terdaftar Resmi, Best Intern, Cumlaude')
                                    ->default('Terverifikasi')
                                    ->maxLength(100)
                                    ->nullable(),
                            ]),

                            Textarea::make('description')
                                ->label('Ringkasan / Deskripsi Lengkap Capaian')
                                ->placeholder('Jelaskan rincian sertifikasi, penghargaan, kontribusi nyata, atau dampak capaian...')
                                ->rows(4)
                                ->nullable(),
                        ]),

                    Section::make('Unggah Berkas & Dokumentasi')
                        ->description('Multi-upload foto/gambar sertifikat dan berkas dokumen PDF resmi')
                        ->icon('heroicon-o-photo')
                        ->collapsible()
                        ->schema([
                            FileUpload::make('images')
                                ->label('Unggah Gambar / Foto Sertifikat (Bisa Lebih dari 1)')
                                ->helperText('Dapat memilih & mengunggah banyak foto/gambar sekaligus. Gambar pertama otomatis menjadi sampul kartu.')
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('certifications/images')
                                ->visibility('public')
                                ->panelLayout('grid')
                                ->openable()
                                ->downloadable()
                                ->maxSize(10240)
                                ->nullable(),

                            FileUpload::make('files')
                                ->label('Unggah Berkas / Dokumen Resmi (Bisa Lebih dari 1)')
                                ->helperText('Dokumen PDF Surat Pencatatan, SK Lisensi, Sertifikat PDF, Arsip ZIP, dsb.')
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->openable()
                                ->downloadable()
                                ->disk('public')
                                ->directory('certifications/files')
                                ->visibility('public')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/zip',
                                    'application/x-zip-compressed',
                                    'application/octet-stream',
                                ])
                                ->maxSize(25600)
                                ->nullable(),
                        ]),

                    Section::make('Aksi Tombol Kartu & Navigasi (Opsional)')
                        ->description('Tautan tombol interaktif pada kartu sertifikasi')
                        ->icon('heroicon-o-cursor-arrow-ripple')
                        ->collapsible()
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('action_label')
                                    ->label('Label Teks Tombol Aksi')
                                    ->placeholder('Contoh: Lihat Sistem, Detail Magang, Buka Arsip')
                                    ->maxLength(100)
                                    ->nullable(),

                                TextInput::make('action_url')
                                    ->label('Tautan Tombol Aksi')
                                    ->placeholder('Contoh: #proyek, #magang, atau URL eksternal')
                                    ->maxLength(255)
                                    ->nullable(),
                            ]),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 2]),

                Group::make([
                    Section::make('Status Publikasi & Urutan')
                        ->description('Visibilitas di halaman portofolio')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Toggle::make('is_published')
                                ->label('Publikasikan')
                                ->helperText('Tampilkan di website portofolio')
                                ->default(true),

                            Toggle::make('is_featured')
                                ->label('Tandai sebagai Unggulan')
                                ->default(false),

                            Grid::make(2)->schema([
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),

                                DateTimePicker::make('published_at')
                                    ->label('Waktu Publikasi')
                                    ->default(now()),
                            ]),
                        ]),

                    Section::make('Logo Instansi / Kredensial')
                        ->description('Logo resmi instansi penerbit sertifikat')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            FileUpload::make('logo_path')
                                ->label('Logo Instansi')
                                ->helperText('PNG, JPG, WebP, SVG.')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('certifications/logos')
                                ->visibility('public')
                                ->maxSize(5120)
                                ->nullable(),
                        ]),

                    Section::make('Verifikasi & Kredensial')
                        ->description('Nomor lisensi & tautan pengecekan')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            TextInput::make('credential_id')
                                ->label('Nomor Lisensi / Nomor HKI')
                                ->placeholder('Contoh: EC00202512345')
                                ->maxLength(255)
                                ->nullable(),

                            TextInput::make('credential_url')
                                ->label('Tautan Verifikasi Online')
                                ->placeholder('https://verifikasi.kemenkumham.go.id/...')
                                ->url()
                                ->maxLength(500)
                                ->nullable(),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
