<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DocumentationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Documentation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'description',
        'type',
        'file_path',
        'preview_image_path',
        'external_url',
        'project_id',
        'is_featured',
        'is_published',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => DocumentationType::class,
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function isImage(): bool
    {
        return $this->type === DocumentationType::Image;
    }

    public function isDocument(): bool
    {
        return $this->type === DocumentationType::Document;
    }

    public function isLink(): bool
    {
        return $this->type === DocumentationType::Link;
    }

    protected function filePath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFilePaths(),
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
    public function getFilePaths(): array
    {
        $val = $this->attributes['file_path'] ?? null;
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

    public function getFirstFilePath(): ?string
    {
        $paths = $this->getFilePaths();

        return $paths[0] ?? null;
    }

    public function hasFiles(): bool
    {
        return count($this->getFilePaths()) > 0;
    }

    public function getFileCount(): int
    {
        return count($this->getFilePaths());
    }

    /**
     * @return array<int, array{index: int, path: string, name: string, extension: string, is_image: bool, url: string, exists: bool}>
     */
    public function getFileItems(): array
    {
        $items = [];
        foreach ($this->getFilePaths() as $index => $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: '');
            $isImg = in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif']);
            $exists = Storage::disk('public')->exists($path);
            $items[] = [
                'index' => $index,
                'path' => $path,
                'name' => basename($path),
                'extension' => $ext,
                'is_image' => $isImg,
                'url' => asset('storage/'.$path),
                'exists' => $exists,
            ];
        }

        return $items;
    }

    public function getDownloadFilename(?int $index = null): string
    {
        $paths = $this->getFilePaths();
        $targetPath = ($index !== null && isset($paths[$index])) ? $paths[$index] : ($paths[0] ?? null);
        $extension = $targetPath ? strtolower(pathinfo($targetPath, PATHINFO_EXTENSION) ?: '') : '';
        $suffix = ($index !== null && count($paths) > 1) ? '-part'.($index + 1) : '';

        return Str::slug($this->title).$suffix.($extension ? '.'.$extension : '');
    }

    public function getFileExtension(?string $path = null): string
    {
        $target = $path ?? $this->getFirstFilePath();
        if (! $target) {
            return '';
        }

        return strtolower(pathinfo($target, PATHINFO_EXTENSION) ?: '');
    }

    public function getEffectivePreviewUrl(): ?string
    {
        if ($this->preview_image_path) {
            return asset('storage/'.$this->preview_image_path);
        }

        foreach ($this->getFilePaths() as $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: '');
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'])) {
                return asset('storage/'.$path);
            }
        }

        return null;
    }
}
