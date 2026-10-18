<?php

declare(strict_types=1);

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Utama')
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun Pengguna')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('headline')
                            ->label('Headline Utama')
                            ->placeholder('Contoh: Senior Full Stack Web Developer & Problem Solver')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->placeholder('Contoh: Crafting robust and elegant digital solutions')
                            ->maxLength(255),
                        Textarea::make('bio')
                            ->label('Biografi / Tentang Saya')
                            ->rows(4),
                        Toggle::make('is_open_to_work')
                            ->label('Status Terbuka untuk Tawaran Kerja (Open to Work)')
                            ->default(true),
                    ]),

                Section::make('Kontak & Lokasi')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email Kontak Publik')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Nomor Telepon / WhatsApp')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('location')
                            ->label('Lokasi Domisili')
                            ->placeholder('Jakarta, Indonesia')
                            ->maxLength(255),
                    ])->columns(3),

                Section::make('Ungkapan Favorit (Quote Section)')
                    ->schema([
                        Textarea::make('quote_text')
                            ->label('Isi Ungkapan / Quote')
                            ->rows(2),
                        TextInput::make('quote_author')
                            ->label('Penulis / Sumber Quote')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Foto Profil & Berkas CV')
                    ->schema([
                        FileUpload::make('photo_path')
                            ->label('Foto Profil Utama')
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
                        FileUpload::make('cv_path')
                            ->label('Berkas CV (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('profiles/cv')
                            ->visibility('public')
                            ->maxSize(10240),
                    ])->columns(2),
            ]);
    }
}
