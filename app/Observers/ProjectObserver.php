<?php

declare(strict_types=1);

namespace App\Observers;

use App\Contracts\AttachmentStorage;
use App\Models\Project;

final class ProjectObserver
{
    public function __construct(
        private readonly AttachmentStorage $storage
    ) {}

    public function deleting(Project $project): void
    {
        if ($project->thumbnail_path) {
            $this->storage->delete($project->thumbnail_path);
        }

        foreach ($project->media as $media) {
            if ($media->path) {
                $this->storage->delete($media->path);
            }
        }
    }
}
