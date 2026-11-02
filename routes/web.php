<?php

declare(strict_types=1);

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.home');
Route::get('/proyek/{slug}', [PortfolioController::class, 'project'])->name('portfolio.project.show');
Route::get('/proyek/{slug}/download-thumbnail', [PortfolioController::class, 'downloadThumbnail'])->name('portfolio.project.download-thumbnail');
Route::get('/media/{id}/download', [PortfolioController::class, 'downloadMedia'])->name('portfolio.media.download');
Route::get('/pengalaman/{slug}', [PortfolioController::class, 'experience'])->name('portfolio.experience.show');

Route::get('/dokumentasi', [PortfolioController::class, 'documentations'])->name('portfolio.documentations.index');
Route::get('/dokumentasi/{slug}', [PortfolioController::class, 'documentationDetail'])->name('portfolio.documentations.show');
Route::get('/dokumentasi/{slug}/preview', [PortfolioController::class, 'previewDocumentation'])->name('portfolio.documentations.preview');
Route::get('/dokumentasi/{slug}/download', [PortfolioController::class, 'downloadDocumentation'])->name('portfolio.documentations.download');

Route::get('/sertifikasi/{slug}/berkas/{index?}', [PortfolioController::class, 'downloadCertificationFile'])->name('portfolio.certifications.download-file');
Route::get('/sertifikasi/{slug}/gambar/{index?}', [PortfolioController::class, 'downloadCertificationImage'])->name('portfolio.certifications.download-image');

Route::get('/sitemap.xml', [PortfolioController::class, 'sitemap'])->name('portfolio.sitemap');

Route::post('/kontak', [PortfolioController::class, 'sendMessage'])
    ->middleware('throttle:5,1')
    ->name('portfolio.contact.send');

Route::post('/kontak/verifikasi-wa', [PortfolioController::class, 'verifyWhatsApp'])
    ->middleware('throttle:30,1')
    ->name('portfolio.contact.verify-wa');

Route::get('/pesan/{message}/lampiran/{index?}', [PortfolioController::class, 'downloadMessageAttachment'])
    ->name('portfolio.messages.download-attachment');

// Fallback for legacy images previously cached by Google crawler (e.g. ppa.jpg)
Route::get('/images/{file}', function (string $file) {
    $fallbackFile = public_path('images/'.$file);
    if (file_exists($fallbackFile)) {
        $mime = match (pathinfo($fallbackFile, PATHINFO_EXTENSION)) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'image/jpeg',
        };

        return response()->file($fallbackFile, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    $faviconPng = public_path('favicon.png');
    if (file_exists($faviconPng)) {
        return response()->file($faviconPng, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    return redirect(asset('favicon.ico'), 301);
})->where('file', '.*');
