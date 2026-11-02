<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Profile extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('portfolio_home_data');
        });

        static::deleted(function () {
            Cache::forget('portfolio_home_data');
        });
    }

    protected $fillable = [
        'user_id',
        'full_name',
        'headline',
        'tagline',
        'bio',
        'photo_path',
        'cv_path',
        'email',
        'phone',
        'location',
        'quote_text',
        'quote_author',
        'is_open_to_work',
    ];

    protected function casts(): array
    {
        return [
            'is_open_to_work' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }
}
