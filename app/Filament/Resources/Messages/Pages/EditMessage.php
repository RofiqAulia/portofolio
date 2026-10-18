<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Messages\MessageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMessage extends EditRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
