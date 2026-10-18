<?php

declare(strict_types=1);

namespace App\Filament\Resources\SkillCategories\Schemas;

use App\Enums\SkillType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class SkillCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Jenis Keahlian')
                    ->options([
                        SkillType::Hard->value => SkillType::Hard->label(),
                        SkillType::Soft->value => SkillType::Soft->label(),
                        SkillType::Analysis->value => SkillType::Analysis->label(),
                    ])
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
