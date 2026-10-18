<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documentations\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Documentations\DocumentationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentation extends CreateRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = DocumentationResource::class;
}
