<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AttachmentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperienceAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_id',
        'type',
        'title',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'url',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'type' => AttachmentType::class,
            'file_size' => 'integer',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function isFile(): bool
    {
        return $this->type === AttachmentType::File;
    }

    public function isLink(): bool
    {
        return $this->type === AttachmentType::Link;
    }
}
