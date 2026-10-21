<?php

declare(strict_types=1);

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Penghargaan & Sertifikasi')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Penghargaan / Sertifikasi')
                            ->placeholder('Contoh: Sistem Manajemen Beasiswa SOVIA atau Best Performance Backend Engineer')
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
                            ->placeholder('Contoh: DJKI Kemenkumham RI / Forum Human Capital Indonesia / BUMN')
                            ->maxLength(255)
                            ->nullable(),

                        FileUpload::make('logo_path')
                            ->label('Logo Instansi / Kredensial')
                            ->helperText('Unggah logo instansi atau simbol resmi (PNG, JPG, WebP, SVG).')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('certifications/logos')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->nullable(),

                        TextInput::make('issued_year')
                            ->label('Tahun / Periode')
                            ->placeholder('Contoh: 2025, 2025 - 2026, atau IPK 3.68/4.00')
                            ->maxLength(100)
                            ->nullable(),

                        TextInput::make('status_label')
                            ->label('Label Status / Badge')
                            ->placeholder('Contoh: Terdaftar Resmi, Lulus Terverifikasi, Best Intern, Cumlaude')
                            ->default('Terverifikasi')
                            ->maxLength(100)
                            ->nullable(),

                        TextInput::make('credential_id')
                            ->label('Nomor Lisensi / Nomor Pencatatan HKI')
                            ->placeholder('Contoh: EC00202512345 atau FHCI-2026-098')
                            ->maxLength(255)
                            ->nullable(),

                        TextInput::make('credential_url')
                            ->label('Tautan Verifikasi Online (Credential URL)')
                            ->placeholder('https://verifikasi.kemenkumham.go.id/...')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),

                        Textarea::make('description')
                            ->label('Ringkasan / Deskripsi Lengkap Capaian')
                            ->placeholder('Jelaskan rincian sertifikasi, penghargaan, kontribusi nyata, atau dampak capaian...')
                            ->rows(4)
                            ->columnSpanFull()
                            ->nullable(),
                    ])->columns(2),

                Section::make('Unggah Berkas & Gambar (Dukungan Multi-Upload)')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Unggah Gambar / Foto Sertifikat (Bisa Lebih dari 1)')
                            ->helperText('Dapat memilih & mengunggah banyak foto/gambar sekaligus (PNG, JPG, WebP, SVG). Gambar pertama otomatis menjadi sampul kartu.')
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
                            ->columnSpanFull()
                            ->nullable(),

                        FileUpload::make('files')
                            ->label('Unggah Berkas / Dokumen Resmi (Bisa Lebih dari 1)')
                            ->helperText('Dapat mengunggah banyak dokumen sekaligus (PDF Surat Pencatatan, SK Lisensi, Sertifikat PDF, Arsip ZIP, dsb).')
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
                            ->columnSpanFull()
                            ->nullable(),
                    ]),

                Section::make('Aksi Tombol Kartu & Navigasi (Opsional)')
                    ->schema([
                        TextInput::make('action_url')
                            ->label('Tautan Tombol Aksi')
                            ->placeholder('Contoh: #proyek, #magang, #organisasi, #tentang, atau URL eksternal')
                            ->maxLength(255)
                            ->nullable(),

                        TextInput::make('action_label')
                            ->label('Label Teks Tombol Aksi')
                            ->placeholder('Contoh: Lihat Sistem, Detail Magang, Profil Lulusan, Buka Arsip')
                            ->maxLength(100)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Status Publikasi & Urutan')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->helperText('Tampilkan di website portofolio publik')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Tandai sebagai Unggulan')
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
