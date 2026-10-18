<?php

declare(strict_types=1);

namespace App\Filament\Resources\Experiences\RelationManagers;

use App\Enums\AttachmentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Lampiran & Berkas Bukti';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Jenis Lampiran')
                    ->options([
                        AttachmentType::File->value => AttachmentType::File->label(),
                        AttachmentType::Link->value => AttachmentType::Link->label(),
                    ])
                    ->required()
                    ->live(),
                TextInput::make('title')
                    ->label('Judul Lampiran')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('file_path')
                    ->label('Unggah Berkas (PDF, DOCX, JPG, PNG - Maks 10MB)')
                    ->directory('attachments')
                    ->disk('public')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->maxSize(10240)
                    ->visibility('public')
                    ->visible(fn ($get) => $get('type') === AttachmentType::File->value)
                    ->required(fn ($get, string $operation) => $get('type') === AttachmentType::File->value && $operation === 'create'),
                TextInput::make('url')
                    ->label('Alamat URL Tautan')
                    ->url()
                    ->maxLength(500)
                    ->visible(fn ($get) => $get('type') === AttachmentType::Link->value)
                    ->required(fn ($get) => $get('type') === AttachmentType::Link->value),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_visible')
                    ->label('Tampilkan ke Publik')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Lampiran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => AttachmentType::File->value,
                        'info' => AttachmentType::Link->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof AttachmentType ? $state->label() : ($state ?? '')),
                TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => $state ? basename((string) $state) : '-')
                    ->url(fn ($record) => $record->file_path ? asset('storage/'.$record->file_path) : null, true),
                TextColumn::make('url')
                    ->label('Tautan')
                    ->url(fn ($state) => $state, true)
                    ->placeholder('-'),
                IconColumn::make('is_visible')
                    ->label('Tampil')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Lampiran'),
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
