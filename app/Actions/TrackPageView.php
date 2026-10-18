<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\PageViewData;
use App\Models\PageView;

final class TrackPageView
{
    public function execute(PageViewData $data): PageView
    {
        return PageView::create([
            'page' => $data->page,
            'project_id' => $data->projectId,
            'visitor_hash' => $data->visitorHash,
            'referrer' => $data->referrer,
            'user_agent' => $data->userAgent,
            'created_at' => now(),
        ]);
    }
}
