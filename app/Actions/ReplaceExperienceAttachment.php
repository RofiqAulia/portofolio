<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\AttachmentType;
use App\Models\ExperienceAttachment;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;

final class ReplaceExperienceAttachment
{
    public function __construct(
        private readonly AttachmentService $attachmentService
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(
        ExperienceAttachment $attachment,
        array $attributes,
        ?UploadedFile $file = null
    ): ExperienceAttachment {
        $type = $attributes['type'] instanceof AttachmentType
            ? $attributes['type']
            : AttachmentType::from((string) $attributes['type']);

        $handler = $this->attachmentService->resolveHandler($type);
        $payload = $handler->handle($attributes, $file, $attachment);

        $attachment->update($payload);

        return $attachment;
    }
}
