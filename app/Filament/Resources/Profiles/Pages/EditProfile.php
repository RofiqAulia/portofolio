<?php

namespace App\Filament\Resources\Profiles\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Profiles\ProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProfile extends EditRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = ProfileResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Identitas & Informasi Profil';
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
