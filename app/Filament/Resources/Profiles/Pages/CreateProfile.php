<?php

namespace App\Filament\Resources\Profiles\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Profiles\ProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProfile extends CreateRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = ProfileResource::class;
}
