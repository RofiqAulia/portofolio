<?php

declare(strict_types=1);

namespace App\Filament\Resources\Technologies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class TechnologyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Teknologi & Alat Kerja')
                    ->description('Bahasa pemrograman, framework, database, atau tools pengembangan yang dikuasai')
                    ->icon('heroicon-o-cpu-chip')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            TextInput::make('name')
                                ->label('Nama Teknologi / Keahlian')
                                ->placeholder('Contoh: Laravel, Docker, PostgreSQL, Vue.js')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state ?? '')) : null),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->helperText('Otomatis disinkronkan dari nama teknologi')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),

                        FileUpload::make('icon_path')
                            ->label('Logo / Ikon')
                            ->helperText('Unggah berkas logo resmi (PNG, JPG, SVG, WebP). Jika dikosongkan, nama keahlian ditampilkan sebagai teks badge.')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->imageResizeMode('contain')
                            ->maxSize(4096)
                            ->disk('public')
                            ->directory('logo')
                            ->visibility('public')
                            ->nullable(),
                    ]),
            ]);
    }
}
