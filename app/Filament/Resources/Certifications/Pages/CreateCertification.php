<?php

declare(strict_types=1);

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Concerns\RedirectsToPreviousUrl;
use App\Filament\Resources\Certifications\CertificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertification extends CreateRecord
{
    use RedirectsToPreviousUrl;

    protected static string $resource = CertificationResource::class;
}
