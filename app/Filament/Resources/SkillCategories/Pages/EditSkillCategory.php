<?php

namespace App\Filament\Resources\SkillCategories\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\SkillCategories\SkillCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSkillCategory extends EditRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = SkillCategoryResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Kategori Keahlian';
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-o-tag';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
