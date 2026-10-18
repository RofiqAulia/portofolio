<?php

namespace App\Filament\Resources\SkillCategories\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\SkillCategories\SkillCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSkillCategory extends CreateRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = SkillCategoryResource::class;
}
