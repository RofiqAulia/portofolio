<?php

declare(strict_types=1);

namespace App\Observers;

use App\Contracts\AttachmentStorage;
use App\Models\ExperienceAttachment;

final class ExperienceAttachmentObserver
{
    public function __construct(
        private readonly AttachmentStorage $storage
    ) {}

    public function deleting(ExperienceAttachment $attachment): void
    {
        if ($attachment->file_path) {
            $this->storage->delete($attachment->file_path);
        }
    }
}
