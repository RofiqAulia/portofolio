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
                    ->label('Nama Teknologi')
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
                    ->label('Ikon / Logo')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                    ])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('400')
                    ->imageResizeTargetHeight('400')
                    ->imageResizeUpscale(false)
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('technologies')
                    ->visibility('public')
                    ->nullable(),
            ]);
    }
}
