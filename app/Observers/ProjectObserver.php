<?php

declare(strict_types=1);

namespace App\Observers;

use App\Contracts\AttachmentStorage;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

final class ProjectObserver
{
    public function __construct(
        private readonly AttachmentStorage $storage
    ) {}

    public function saved(Project $project): void
    {
        Cache::forget('portfolio_home_data');
    }

    public function deleted(Project $project): void
    {
        Cache::forget('portfolio_home_data');
    }

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
