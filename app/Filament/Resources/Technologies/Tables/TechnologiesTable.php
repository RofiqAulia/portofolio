<?php

declare(strict_types=1);

namespace App\Filament\Resources\Technologies\Tables;

use App\Models\Technology;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class TechnologiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('icon_path')
                    ->label('Logo')
                    ->disk('public')
                    ->defaultImageUrl(fn (Technology $record) => $record->logo_url)
                    ->square()
                    ->height(40)
                    ->width(40),
                TextColumn::make('name')
                    ->label('Nama Keahlian')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
