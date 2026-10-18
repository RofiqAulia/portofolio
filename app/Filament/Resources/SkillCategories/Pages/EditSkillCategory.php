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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
