<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Messages\MessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMessage extends CreateRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = MessageResource::class;
}
