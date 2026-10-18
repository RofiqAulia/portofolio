<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\ExperienceAttachment;
use Illuminate\Http\UploadedFile;

interface AttachmentHandler
{
    /**
     * Process and fill data for attachment.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function handle(array $data, ?UploadedFile $file = null, ?ExperienceAttachment $existing = null): array;
}
