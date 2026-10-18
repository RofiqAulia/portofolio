<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\AttachmentStorage;
use App\Models\Experience;
use App\Models\ExperienceAttachment;
use App\Models\Project;
use App\Observers\ExperienceAttachmentObserver;
use App\Observers\ExperienceObserver;
use App\Observers\ProjectObserver;
use App\Services\LocalAttachmentStorage;
use App\Services\VisitorHasher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AttachmentStorage::class, function () {
            return new LocalAttachmentStorage(Storage::disk('public'));
        });

        $this->app->singleton(VisitorHasher::class, function () {
            return new VisitorHasher((string) config('app.key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Project::observe(ProjectObserver::class);
        Experience::observe(ExperienceObserver::class);
        ExperienceAttachment::observe(ExperienceAttachmentObserver::class);
    }
}
