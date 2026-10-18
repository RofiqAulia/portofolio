<?php

namespace App\Filament\Resources\Technologies\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Technologies\TechnologyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTechnology extends CreateRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = TechnologyResource::class;
}
