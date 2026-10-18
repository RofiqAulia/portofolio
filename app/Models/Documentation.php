<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DocumentationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function getDownloadFilename(): string
    {
        $extension = $this->getFileExtension();

        return Str::slug($this->title).($extension ? '.'.$extension : '');
    }

    public function getFileExtension(): string
    {
        if (! $this->file_path) {
            return '';
        }

        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION) ?: '');
    }

    public function getEffectivePreviewUrl(): ?string
    {
        if ($this->preview_image_path) {
            return asset('storage/'.$this->preview_image_path);
        }

        if ($this->isImage() && $this->file_path) {
            return asset('storage/'.$this->file_path);
        }

        return null;
    }
}
