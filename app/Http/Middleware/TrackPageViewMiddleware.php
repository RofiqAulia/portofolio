<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\TrackPageView;
use App\Data\PageViewData;
use App\Models\Project;
use App\Services\VisitorHasher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class TrackPageViewMiddleware
{
    public function __construct(
        private readonly VisitorHasher $hasher,
        private readonly TrackPageView $tracker
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track only successful GET requests to public pages (exclude admin, api, livewire, debug)
        if ($request->isMethod('GET') && $response->isSuccessful() && ! $request->is('admin*', 'livewire*', 'storage*', 'up')) {
            $ip = (string) ($request->ip() ?? '127.0.0.1');
            $ua = $request->userAgent();
            $hash = $this->hasher->hash($ip, $ua);

            $projectId = null;
            if ($request->route('slug')) {
                $slug = (string) $request->route('slug');
                $projectId = Project::where('slug', $slug)->value('id');
            }

            $this->tracker->execute(new PageViewData(
                page: '/'.ltrim($request->path(), '/'),
                visitorHash: $hash,
                projectId: $projectId,
                referrer: $request->header('referer'),
                userAgent: $ua,
            ));
        }

        return $response;
    }
}
