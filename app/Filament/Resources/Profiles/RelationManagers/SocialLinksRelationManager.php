<?php

declare(strict_types=1);

namespace App\Filament\Resources\Profiles\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SocialLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'socialLinks';

    protected static ?string $title = 'Tautan Media Sosial';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('platform')
                    ->label('Nama Platform (misal: GitHub, LinkedIn)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('icon')
                    ->label('Ikon / Kelas Icon (misal: bi-github)')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('url')
                    ->label('Alamat URL')
                    ->url()
                    ->required()
                    ->maxLength(500),
                TextInput::make('sort_order')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktifkan')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('platform')
            ->columns([
                TextColumn::make('platform')
                    ->label('Platform')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('icon')
                    ->label('Ikon'),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(35)
                    ->url(fn ($state) => $state, true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Tautan Sosial'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
