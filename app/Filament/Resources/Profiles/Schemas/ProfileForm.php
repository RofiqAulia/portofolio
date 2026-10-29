<?php

declare(strict_types=1);

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Identitas & Tentang Saya')
                        ->description('Profil nama, headline profesional, dan narasi biografi')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                TextInput::make('full_name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Contoh: Muhammad Rofiq Aulia')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('headline')
                                    ->label('Headline Utama')
                                    ->placeholder('Contoh: Senior Full Stack Web Developer & Problem Solver')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                            Textarea::make('tagline')
                                ->label('Tagline / Sub-title')
                                ->placeholder('Contoh: Sarjana Terapan Teknik Informatika berfokus pada arsitektur web modern...')
                                ->rows(2),

                            Textarea::make('bio')
                                ->label('Biografi / Tentang Saya')
                                ->placeholder('Tulis deskripsi mendalam mengenai perjalanan karir, fokus keahlian, dan dedikasi profesional...')
                                ->rows(5),
                        ]),

                    Section::make('Kontak Publik & Domisili')
                        ->description('Informasi jalur komunikasi untuk kolaborasi dan rekrutmen')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 3])->schema([
                                TextInput::make('email')
                                    ->label('Email Kontak')
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('Nomor WhatsApp / Telp')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->tel()
                                    ->maxLength(50),

                                TextInput::make('location')
                                    ->label('Lokasi Domisili')
                                    ->prefixIcon('heroicon-o-globe-americas')
                                    ->placeholder('Jakarta, Indonesia')
                                    ->maxLength(255),
                            ]),
                        ]),

                    Section::make('Kutipan Inspiratif (Quote Section)')
                        ->description('Pernyataan filosofi kerja yang ditampilkan di portofolio')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->collapsible()
                        ->schema([
                            Grid::make(['default' => 1, 'md' => 2])->schema([
                                Textarea::make('quote_text')
                                    ->label('Isi Ungkapan / Kutipan')
                                    ->placeholder('Simplicity is prerequisite for reliability...')
                                    ->rows(2),

                                TextInput::make('quote_author')
                                    ->label('Penulis / Sumber Kutipan')
                                    ->placeholder('Edsger W. Dijkstra')
                                    ->maxLength(255),
                            ]),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 2]),

                Group::make([
                    Section::make('Akun & Ketersediaan')
                        ->description('Kesiapan karir dan akun terhubung')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Select::make('user_id')
                                ->label('Akun Pengguna')
                                ->relationship('user', 'name')
                                ->required()
                                ->searchable(),

                            Toggle::make('is_open_to_work')
                                ->label('Status Terbuka untuk Tawaran Kerja (Open to Work)')
                                ->helperText('Menampilkan badge "Available for Opportunities" di website')
                                ->default(true),
                        ]),

                    Section::make('Foto Profil Utama')
                        ->description('Foto formal untuk hero & kartu profil')
                        ->icon('heroicon-o-camera')
                        ->schema([
                            FileUpload::make('photo_path')
                                ->label('Foto Profil (Avatar)')
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '1:1',
                                ])
                                ->imageResizeMode('cover')
                                ->imageResizeTargetWidth('600')
                                ->imageResizeTargetHeight('600')
                                ->imageResizeUpscale(false)
                                ->maxSize(5120)
                                ->disk('public')
                                ->directory('profiles/photos')
                                ->visibility('public'),
                        ]),

                    Section::make('Berkas Curriculum Vitae (CV)')
                        ->description('Dokumen resume PDF untuk diunduh pengunjung')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            FileUpload::make('cv_path')
                                ->label('Berkas CV (PDF)')
                                ->acceptedFileTypes(['application/pdf'])
                                ->openable()
                                ->downloadable()
                                ->disk('public')
                                ->directory('profiles/cv')
                                ->visibility('public')
                                ->maxSize(10240),
                        ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
