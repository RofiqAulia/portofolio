<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documentations\Schemas;

use App\Enums\DocumentationType;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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
                        Select::make('project_id')
                            ->label('Terkait Proyek (Hubungkan ke Proyek)')
                            ->helperText('Pilih proyek untuk menghubungkan blueprint ini dan menarik judul proyek secara otomatis.')
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (! $state) {
                                    return;
                                }

                                $project = Project::find($state);
                                if (! $project) {
                                    return;
                                }

                                $category = $get('category') ?? 'ERD & Database';
                                $prefix = match ($category) {
                                    'ERD & Database' => 'Desain ERD & Skema Database',
                                    'UML & Flowchart' => 'Diagram UML & Sequence Alur',
                                    'Arsitektur Sistem' => 'Arsitektur Sistem & Blueprint',
                                    'Software Design Pattern' => 'Pola Desain & OOP Architecture',
                                    'Dokumentasi API & SRS' => 'Spesifikasi Sistem (SRS) & API',
                                    'Panduan & SOP' => 'Panduan Teknis & SOP Sistem',
                                    default => 'Dokumentasi Sistem',
                                };

                                if (empty($get('title'))) {
                                    $newTitle = "{$prefix} — {$project->title}";
                                    $set('title', $newTitle);
                                    $set('slug', Str::slug($newTitle));
                                }

                                if (empty($get('summary'))) {
                                    $set('summary', "Dokumentasi spesifikasi teknis dan blueprint arsitektur sistem untuk proyek {$project->title}.");
                                }
                            }),

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
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $projectId = $get('project_id');
                                if (! $projectId) {
                                    return;
                                }

                                if (empty($get('title'))) {
                                    $project = Project::find($projectId);
                                    if ($project) {
                                        $prefix = match ($state) {
                                            'ERD & Database' => 'Desain ERD & Skema Database',
                                            'UML & Flowchart' => 'Diagram UML & Sequence Alur',
                                            'Arsitektur Sistem' => 'Arsitektur Sistem & Blueprint',
                                            'Software Design Pattern' => 'Pola Desain & OOP Architecture',
                                            'Dokumentasi API & SRS' => 'Spesifikasi Sistem (SRS) & API',
                                            'Panduan & SOP' => 'Panduan Teknis & SOP Sistem',
                                            default => 'Dokumentasi Sistem',
                                        };
                                        $newTitle = "{$prefix} — {$project->title}";
                                        $set('title', $newTitle);
                                        $set('slug', Str::slug($newTitle));
                                    }
                                }
                            }),

                        TextInput::make('title')
                            ->label('Judul Dokumentasi / Blueprint')
                            ->placeholder('Contoh: Desain ERD & Skema Database — Sistem Informasi Logistik')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            })
                            ->hintAction(
                                Action::make('copyTitleFromProject')
                                    ->label('Ambil dari Judul Proyek')
                                    ->icon('heroicon-m-sparkles')
                                    ->tooltip('Otomatis gunakan judul proyek dan format kategori untuk nama blueprint ini')
                                    ->action(function (callable $set, callable $get) {
                                        $projectId = $get('project_id');
                                        if (! $projectId) {
                                            Notification::make()
                                                ->title('Pilih Proyek Terlebih Dahulu')
                                                ->body('Silakan pilih salah satu proyek pada dropdown "Terkait Proyek" di atas.')
                                                ->warning()
                                                ->send();

                                            return;
                                        }

                                        $project = Project::find($projectId);
                                        if (! $project) {
                                            return;
                                        }

                                        $category = $get('category') ?? 'ERD & Database';
                                        $prefix = match ($category) {
                                            'ERD & Database' => 'Desain ERD & Skema Database',
                                            'UML & Flowchart' => 'Diagram UML & Sequence Alur',
                                            'Arsitektur Sistem' => 'Arsitektur Sistem & Blueprint',
                                            'Software Design Pattern' => 'Pola Desain & OOP Architecture',
                                            'Dokumentasi API & SRS' => 'Spesifikasi Sistem (SRS) & API',
                                            'Panduan & SOP' => 'Panduan Teknis & SOP Sistem',
                                            default => 'Dokumentasi Sistem',
                                        };

                                        $newTitle = "{$prefix} — {$project->title}";
                                        $set('title', $newTitle);
                                        $set('slug', Str::slug($newTitle));

                                        if (empty($get('summary'))) {
                                            $set('summary', "Dokumentasi spesifikasi teknis dan blueprint arsitektur sistem untuk proyek {$project->title}.");
                                        }

                                        Notification::make()
                                            ->title('Judul Berhasil Diambil!')
                                            ->body("Judul diatur: {$newTitle}")
                                            ->success()
                                            ->send();
                                    })
                            ),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

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
                            ->helperText('Dapat mengunggah lebih dari 1 berkas (misal: beberapa gambar diagram ERD/UML, PDF spesifikasi, atau arsip ZIP).')
                            ->disk('public')
                            ->directory('documentations/files')
                            ->visibility('public')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->openable()
                            ->downloadable()
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
