<?php

declare(strict_types=1);

namespace App\Filament\Resources\Technologies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class TechnologyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Teknologi / Keahlian')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state ?? '')) : null),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                FileUpload::make('icon_path')
                    ->label('Logo / Ikon')
                    ->helperText('Unggah berkas logo (PNG, JPG, SVG, WebP). Jika dikosongkan, keahlian akan ditampilkan dalam bentuk teks di landing page.')
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
            ]);
    }
}
