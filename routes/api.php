<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\PortfolioApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Read-only endpoints throttled to 60 requests per minute
    Route::middleware('throttle:60,1')->group(function (): void {
        Route::get('/profile', [PortfolioApiController::class, 'profile'])->name('api.v1.profile');
        Route::get('/social-links', [PortfolioApiController::class, 'socialLinks'])->name('api.v1.social-links');
        Route::get('/skills', [PortfolioApiController::class, 'skills'])->name('api.v1.skills');
        Route::get('/projects', [PortfolioApiController::class, 'projects'])->name('api.v1.projects.index');
        Route::get('/projects/{slug}', [PortfolioApiController::class, 'project'])->name('api.v1.projects.show');
        Route::get('/experiences', [PortfolioApiController::class, 'experiences'])->name('api.v1.experiences.index');
        Route::get('/experiences/{id}', [PortfolioApiController::class, 'experience'])->name('api.v1.experiences.show');
    });

    // Contact form endpoint throttled to 5 requests per minute
    Route::post('/messages', [PortfolioApiController::class, 'storeMessage'])
        ->middleware('throttle:5,1')
        ->name('api.v1.messages.store');
});
