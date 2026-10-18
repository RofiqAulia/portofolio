<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documentations;

use App\Filament\Resources\Documentations\Pages\CreateDocumentation;
use App\Filament\Resources\Documentations\Pages\EditDocumentation;
use App\Filament\Resources\Documentations\Pages\ListDocumentations;
use App\Filament\Resources\Documentations\Schemas\DocumentationForm;
use App\Filament\Resources\Documentations\Tables\DocumentationsTable;
use App\Models\Documentation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DocumentationResource extends Resource
{
    protected static ?string $model = Documentation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Portofolio';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Dokumentasi & Blueprint';

    protected static ?string $modelLabel = 'Dokumentasi';

    protected static ?string $pluralModelLabel = 'Dokumentasi & Blueprint';

    public static function form(Schema $schema): Schema
    {
        return DocumentationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentations::route('/'),
            'create' => CreateDocumentation::route('/create'),
            'edit' => EditDocumentation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
