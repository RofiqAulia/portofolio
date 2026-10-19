<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\DocumentationType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DocumentationsRelationManager extends RelationManager
{
    protected static string $relationship = 'documentations';

    protected static ?string $title = 'Dokumentasi & Blueprint Arsitektur Terkait';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $livewire) {
                        $project = $livewire->getOwnerRecord();
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
                    }),

                TextInput::make('title')
                    ->label('Judul Dokumentasi / Blueprint')
                    ->default(fn ($livewire) => 'Desain ERD & Skema Database — '.($livewire->getOwnerRecord()?->title ?? 'Proyek'))
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
                    ->default(fn ($livewire) => Str::slug('Desain ERD & Skema Database — '.($livewire->getOwnerRecord()?->title ?? 'Proyek')))
                    ->required()
                    ->maxLength(255),

                Textarea::make('summary')
                    ->label('Ringkasan Singkat')
                    ->default(fn ($livewire) => 'Dokumentasi spesifikasi teknis dan blueprint arsitektur sistem untuk proyek '.($livewire->getOwnerRecord()?->title ?? '').'.')
                    ->rows(3)
                    ->maxLength(500),

                RichEditor::make('description')
                    ->label('Catatan Arsitektur & Spesifikasi')
                    ->nullable(),

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

                Toggle::make('is_published')
                    ->label('Publikasikan')
                    ->default(true),

                Toggle::make('is_featured')
                    ->label('Unggulan')
                    ->default(false),

                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                DateTimePicker::make('published_at')
                    ->label('Waktu Publikasi')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('preview_image_path')
                    ->label('Sampul')
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => $record->getEffectivePreviewUrl())
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul Blueprint')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => DocumentationType::Image->value,
                        'warning' => DocumentationType::Document->value,
                        'info' => DocumentationType::Link->value,
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof DocumentationType ? $state->label() : ($state ?? '')),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Blueprint / Dokumen')
                    ->mutateFormDataUsing(function (array $data, $livewire): array {
                        $data['project_id'] = $livewire->getOwnerRecord()->id;

                        return $data;
                    }),
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
