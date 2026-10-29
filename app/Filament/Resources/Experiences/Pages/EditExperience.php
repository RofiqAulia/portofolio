<?php

namespace App\Filament\Resources\Experiences\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditExperience extends EditRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = ExperienceResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Detail Pengalaman';
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-o-briefcase';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
