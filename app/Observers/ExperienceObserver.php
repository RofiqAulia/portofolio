<?php

declare(strict_types=1);

namespace App\Observers;

use App\Contracts\AttachmentStorage;
use App\Models\Experience;

final class ExperienceObserver
{
    public function __construct(
        private readonly AttachmentStorage $storage
    ) {}

    public function deleting(Experience $experience): void
    {
        if ($experience->cover_path) {
            $this->storage->delete($experience->cover_path);
        }

        foreach ($experience->attachments as $attachment) {
            if ($attachment->file_path) {
                $this->storage->delete($attachment->file_path);
            }
        }

        foreach ($experience->media as $media) {
            if ($media->path) {
                $this->storage->delete($media->path);
            }
        }
    }
}
