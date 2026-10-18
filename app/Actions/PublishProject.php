<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ProjectStatus;
use App\Models\Project;

final class PublishProject
{
    public function execute(Project $project): Project
    {
        $project->update([
            'status' => ProjectStatus::Published,
            'published_at' => $project->published_at ?? now(),
        ]);

        return $project;
    }
}
