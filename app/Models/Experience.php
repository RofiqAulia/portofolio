<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExperienceType;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'position',
        'organization_name',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'summary',
        'story',
        'cover_path',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => ExperienceType::class,
            'status' => ProjectStatus::class,
            'is_current' => 'boolean',
            'sort_order' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Published);
    }

    public function scopeInternships(Builder $query): Builder
    {
        return $query->where('type', ExperienceType::Internship);
    }

    public function scopeOrganizations(Builder $query): Builder
    {
        return $query->where('type', ExperienceType::Organization);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('start_date');
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(ExperienceHighlight::class)->orderBy('sort_order');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ExperienceAttachment::class)->orderBy('sort_order');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'experience_technology');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
    }
}
