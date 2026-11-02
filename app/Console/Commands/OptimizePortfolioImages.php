<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class OptimizePortfolioImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:optimize-images {--dry-run : Only show what would be optimized}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress, resize oversized images, and generate WebP versions for public and storage assets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $directories = [
            storage_path('app/public'),
            public_path('images'),
        ];

        $individualFiles = [
            public_path('icon-512.png'),
            public_path('icon-192.png'),
            public_path('apple-touch-icon.png'),
            public_path('favicon-96x96.png'),
            public_path('favicon.png'),
        ];

        $totalOriginalBytes = 0;
        $totalOptimizedBytes = 0;
        $processedFiles = 0;

        $this->info($dryRun ? 'Running in DRY-RUN mode...' : 'Starting portfolio image optimization...');

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            /** @var SplFileInfo $file */
            foreach ($iterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $filePath = $file->getPathname();
                $extension = strtolower($file->getExtension());

                if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    continue;
                }

                // Skip webp if it's already generated alongside an image
                if ($extension === 'webp') {
                    continue;
                }

                $fileSize = $file->getSize();
                $totalOriginalBytes += $fileSize;

                $maxDim = $this->determineMaxDimension($filePath);
                $result = $this->optimizeImage($filePath, $extension, $maxDim, $dryRun);

                if ($result !== null) {
                    $newSize = $dryRun ? $result['estimated_size'] : filesize($filePath);
                    $totalOptimizedBytes += $newSize;
                    $processedFiles++;

                    $savedPercent = $fileSize > 0 ? round((($fileSize - $newSize) / $fileSize) * 100, 1) : 0;
                    $this->line(sprintf(
                        '<fg=green>✓</> %s: %s -> %s (<fg=yellow>-%s%%</>)',
                        $file->getFilename(),
                        $this->formatBytes($fileSize),
                        $this->formatBytes($newSize),
                        $savedPercent
                    ));
                } else {
                    $totalOptimizedBytes += $fileSize;
                }
            }
        }

        foreach ($individualFiles as $singlePath) {
            if (! file_exists($singlePath)) {
                continue;
            }

            $extension = strtolower(pathinfo($singlePath, PATHINFO_EXTENSION));
            $fileSize = filesize($singlePath);
            $totalOriginalBytes += $fileSize;

            $maxDim = 512;
            $result = $this->optimizeImage($singlePath, $extension, $maxDim, $dryRun);

            if ($result !== null) {
                $newSize = $dryRun ? $result['estimated_size'] : filesize($singlePath);
                $totalOptimizedBytes += $newSize;
                $processedFiles++;

                $savedPercent = $fileSize > 0 ? round((($fileSize - $newSize) / $fileSize) * 100, 1) : 0;
                $this->line(sprintf(
                    '<fg=green>✓</> %s: %s -> %s (<fg=yellow>-%s%%</>)',
                    basename($singlePath),
                    $this->formatBytes($fileSize),
                    $this->formatBytes($newSize),
                    $savedPercent
                ));
            } else {
                $totalOptimizedBytes += $fileSize;
            }
        }

        $savedBytes = max(0, $totalOriginalBytes - $totalOptimizedBytes);
        $overallPercent = $totalOriginalBytes > 0 ? round(($savedBytes / $totalOriginalBytes) * 100, 1) : 0;

        $this->newLine();
        $this->info(sprintf(
            'Optimization completed! Processed %d images. Saved %s in total (-%s%%).',
            $processedFiles,
            $this->formatBytes($savedBytes),
            $overallPercent
        ));

        return self::SUCCESS;
    }

    private function determineMaxDimension(string $filePath): int
    {
        $normalized = str_replace('\\', '/', strtolower($filePath));

        if (str_contains($normalized, 'logo') || str_contains($normalized, 'icon') || str_contains($normalized, 'favicon')) {
            return 320;
        }

        if (str_contains($normalized, 'covers') || str_contains($normalized, 'photos') || str_contains($normalized, 'profile')) {
            return 800;
        }

        // Thumbnails & full gallery
        return 1600;
    }

    /**
     * @return array{estimated_size: int}|null
     */
    private function optimizeImage(string $path, string $ext, int $maxDim, bool $dryRun): ?array
    {
        $imageInfo = @getimagesize($path);
        if ($imageInfo === false) {
            return null;
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;

        if ($origWidth <= 0 || $origHeight <= 0) {
            return null;
        }

        $needsResize = ($origWidth > $maxDim || $origHeight > $maxDim);
        $fileSize = filesize($path);

        // If file is already small and properly sized, skip unless webp is missing
        $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
        $needsWebp = ! file_exists($webpPath);

        if (! $needsResize && $fileSize < 120000 && ! $needsWebp) {
            return null;
        }

        if ($dryRun) {
            return ['estimated_size' => (int) ($fileSize * 0.4)];
        }

        // Load image resource
        $source = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            default => null,
        };

        if (! $source) {
            return null;
        }

        // Calculate new dimensions
        if ($needsResize) {
            $ratio = min($maxDim / $origWidth, $maxDim / $origHeight);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        $dest = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve alpha transparency for PNG
        if ($imageType === IMAGETYPE_PNG) {
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            $transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
            imagefilledrectangle($dest, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($dest, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Save optimized original format
        if ($imageType === IMAGETYPE_JPEG) {
            imagejpeg($dest, $path, 82);
        } elseif ($imageType === IMAGETYPE_PNG) {
            imagepng($dest, $path, 8);
        }

        // Always generate modern WebP alongside it
        if (function_exists('imagewebp')) {
            if ($imageType === IMAGETYPE_PNG) {
                imagepalettetotruecolor($dest);
                imagealphablending($dest, true);
                imagesavealpha($dest, true);
            }
            imagewebp($dest, $webpPath, 82);
        }

        imagedestroy($source);
        imagedestroy($dest);

        clearstatcache(true, $path);

        return ['estimated_size' => filesize($path)];
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}
