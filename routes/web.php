<?php

declare(strict_types=1);

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.home');
Route::get('/proyek/{slug}', [PortfolioController::class, 'project'])->name('portfolio.project.show');
Route::get('/proyek/{slug}/download-thumbnail', [PortfolioController::class, 'downloadThumbnail'])->name('portfolio.project.download-thumbnail');
Route::get('/media/{id}/download', [PortfolioController::class, 'downloadMedia'])->name('portfolio.media.download');
Route::get('/pengalaman/{id}', [PortfolioController::class, 'experience'])->name('portfolio.experience.show');

Route::get('/dokumentasi', [PortfolioController::class, 'documentations'])->name('portfolio.documentations.index');
Route::get('/dokumentasi/{slug}', [PortfolioController::class, 'documentationDetail'])->name('portfolio.documentations.show');
Route::get('/dokumentasi/{slug}/preview', [PortfolioController::class, 'previewDocumentation'])->name('portfolio.documentations.preview');
Route::get('/dokumentasi/{slug}/download', [PortfolioController::class, 'downloadDocumentation'])->name('portfolio.documentations.download');

Route::post('/kontak', [PortfolioController::class, 'sendMessage'])
    ->middleware('throttle:5,1')
    ->name('portfolio.contact.send');
