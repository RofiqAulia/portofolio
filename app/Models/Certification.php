<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Certification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'issuer',
        'issued_year',
        'status_label',
        'logo_path',
        'credential_id',
        'credential_url',
        'action_url',
        'action_label',
        'description',
        'images',
        'files',
        'is_published',
        'is_featured',
        'sort_order',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'files' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if (! empty($category) && $category !== 'all') {
            return $query->where('category', $category);
        }

        return $query;
    }

    /**
     * Accessor & Mutator for images to ensure consistent JSON array handling
     */
    protected function images(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImages(),
            set: function ($value) {
                if (empty($value)) {
                    return null;
                }

                if (is_array($value)) {
                    $filtered = array_values(array_filter($value));

                    return empty($filtered) ? null : json_encode($filtered);
                }

                if (is_string($value) && (str_starts_with(trim($value), '[') || str_starts_with(trim($value), '{'))) {
                    return $value;
                }

                return json_encode([(string) $value]);
            }
        );
    }

    /**
     * Accessor & Mutator for files to ensure consistent JSON array handling
     */
    protected function files(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFiles(),
            set: function ($value) {
                if (empty($value)) {
                    return null;
                }

                if (is_array($value)) {
                    $filtered = array_values(array_filter($value));

                    return empty($filtered) ? null : json_encode($filtered);
                }

                if (is_string($value) && (str_starts_with(trim($value), '[') || str_starts_with(trim($value), '{'))) {
                    return $value;
                }

                return json_encode([(string) $value]);
            }
        );
    }

    /**
     * @return array<int, string>
     */
    public function getImages(): array
    {
        $val = $this->attributes['images'] ?? null;
        if (empty($val)) {
            return [];
        }

        if (is_array($val)) {
            return array_values(array_filter($val));
        }

        $trimmed = trim((string) $val);
        if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
        }

        return [$trimmed];
    }

    /**
     * @return array<int, string>
     */
    public function getFiles(): array
    {
        $val = $this->attributes['files'] ?? null;
        if (empty($val)) {
            return [];
        }

        if (is_array($val)) {
            return array_values(array_filter($val));
        }

        $trimmed = trim((string) $val);
        if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
        }

        return [$trimmed];
    }

    public function hasImages(): bool
    {
        return count($this->getImages()) > 0;
    }

    public function getImageCount(): int
    {
        return count($this->getImages());
    }

    public function hasFiles(): bool
    {
        return count($this->getFiles()) > 0;
    }

    public function getFileCount(): int
    {
        return count($this->getFiles());
    }

    public function getFirstImage(): ?string
    {
        $imgs = $this->getImages();

        return $imgs[0] ?? null;
    }

    public function getFirstImageUrl(): ?string
    {
        $img = $this->getFirstImage();
        if ($img && Storage::disk('public')->exists($img)) {
            return asset('storage/'.$img);
        }

        return null;
    }

    /**
     * @return array<int, array{index: int, path: string, name: string, extension: string, url: string, exists: bool}>
     */
    public function getImageItems(): array
    {
        $items = [];
        foreach ($this->getImages() as $index => $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg');
            $exists = Storage::disk('public')->exists($path);
            $items[] = [
                'index' => $index,
                'path' => $path,
                'name' => basename($path),
                'extension' => $ext,
                'url' => asset('storage/'.$path),
                'exists' => $exists,
            ];
        }

        return $items;
    }

    /**
     * @return array<int, array{index: int, path: string, name: string, extension: string, size: string, url: string, exists: bool}>
     */
    public function getFileItems(): array
    {
        $items = [];
        foreach ($this->getFiles() as $index => $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf');
            $exists = Storage::disk('public')->exists($path);
            $size = '';
            if ($exists) {
                $bytes = Storage::disk('public')->size($path);
                $size = $this->formatBytes($bytes);
            }

            $items[] = [
                'index' => $index,
                'path' => $path,
                'name' => basename($path),
                'extension' => $ext,
                'size' => $size,
                'url' => asset('storage/'.$path),
                'exists' => $exists,
            ];
        }

        return $items;
    }

    public function getDownloadFilename(int $index = 0, string $type = 'file'): string
    {
        $list = $type === 'image' ? $this->getImages() : $this->getFiles();
        $target = $list[$index] ?? null;
        $ext = $target ? strtolower(pathinfo($target, PATHINFO_EXTENSION) ?: '') : '';
        $suffix = count($list) > 1 ? '-part'.($index + 1) : '';

        return Str::slug($this->title).'-'.$type.$suffix.($ext ? '.'.$ext : '');
    }

    public function getLogoUrl(): ?string
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return asset('storage/'.$this->logo_path);
        }

        $first = $this->getFirstImage();
        if ($first && Storage::disk('public')->exists($first)) {
            return asset('storage/'.$first);
        }

        return null;
    }

    public function getCategoryBadgeClass(): string
    {
        return match ($this->category) {
            'Hak Cipta (HKI)' => 'cert-badge cert-badge-hki',
            'Sertifikasi Magang' => 'cert-badge cert-badge-magang',
            'Penghargaan Kinerja' => 'cert-badge cert-badge-kinerja',
            'Kepemimpinan' => 'cert-badge cert-badge-organisasi',
            'Predikat Akademik' => 'cert-badge cert-badge-akademik',
            'Blueprint Kredensial', 'Arsitektur Sistem' => 'cert-badge cert-badge-blueprint',
            default => 'cert-badge cert-badge-default',
        };
    }

    /**
     * @return array{bg: string, text: string, border: string}
     */
    public function getCategoryBadgeStyle(): array
    {
        return match ($this->category) {
            'Hak Cipta (HKI)' => [
                'bg' => 'rgba(14, 165, 233, 0.12)',
                'text' => '#0369a1',
                'border' => 'rgba(14, 165, 233, 0.28)',
            ],
            'Sertifikasi Magang' => [
                'bg' => 'rgba(245, 158, 11, 0.14)',
                'text' => '#b45309',
                'border' => 'rgba(245, 158, 11, 0.35)',
            ],
            'Penghargaan Kinerja' => [
                'bg' => 'rgba(239, 68, 68, 0.12)',
                'text' => '#b91c1c',
                'border' => 'rgba(239, 68, 68, 0.28)',
            ],
            'Kepemimpinan' => [
                'bg' => 'rgba(139, 92, 246, 0.14)',
                'text' => '#6d28d9',
                'border' => 'rgba(139, 92, 246, 0.3)',
            ],
            'Predikat Akademik' => [
                'bg' => 'rgba(16, 185, 129, 0.12)',
                'text' => '#047857',
                'border' => 'rgba(16, 185, 129, 0.28)',
            ],
            'Blueprint Kredensial', 'Arsitektur Sistem' => [
                'bg' => 'rgba(59, 130, 246, 0.12)',
                'text' => '#1d4ed8',
                'border' => 'rgba(59, 130, 246, 0.28)',
            ],
            default => [
                'bg' => 'rgba(100, 116, 139, 0.12)',
                'text' => '#334155',
                'border' => 'rgba(100, 116, 139, 0.28)',
            ],
        };
    }

    public function getCategoryIcon(): string
    {
        return match ($this->category) {
            'Hak Cipta (HKI)' => 'bi-shield-check',
            'Sertifikasi Magang' => 'bi-award-fill',
            'Penghargaan Kinerja' => 'bi-trophy-fill',
            'Kepemimpinan' => 'bi-people-fill',
            'Predikat Akademik' => 'bi-mortarboard-fill',
            'Blueprint Kredensial', 'Arsitektur Sistem' => 'bi-file-earmark-code',
            default => 'bi-patch-check-fill',
        };
    }

    private function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * (int) $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}
