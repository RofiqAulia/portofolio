<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon_path',
    ];

    /**
     * Resolves the logo image URL if available, or returns null.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! empty($this->icon_path)) {
            return asset('storage/'.ltrim($this->icon_path, '/'));
        }

        $slug = strtolower($this->slug ?? Str::slug($this->name ?? ''));

        $possibleFiles = [
            "logo/{$slug}-logo.png",
            "logo/{$slug}-logo.jpg",
            "logo/{$slug}.png",
            "logo/{$slug}.jpg",
        ];

        foreach ($possibleFiles as $file) {
            if (file_exists(public_path('storage/'.$file)) || file_exists(storage_path('app/public/'.$file))) {
                return asset('storage/'.$file);
            }
        }

        $slugMap = [
            'laravel' => 'logo/laravel-logo.jpg',
            'php' => 'logo/php-logo.png',
            'mysql' => 'logo/mysql-logo.png',
            'bootstrap' => 'logo/bootstrap-logo.jpg',
            'tailwind' => 'logo/tailwind-logo.jpg',
            'docker' => 'logo/docker-logo.jpg',
            'git' => 'logo/git-logo.png',
            'notion' => 'logo/notion-logo.png',
            'canva' => 'logo/canva-logo.png',
            'draw' => 'logo/draw-io-logo.png',
            'excel' => 'logo/excel-logo.jpg',
            'word' => 'logo/word-logo.jpg',
            'powerpoint' => 'logo/pptx-logo.jpg',
            'pptx' => 'logo/pptx-logo.jpg',
            'ppt' => 'logo/pptx-logo.jpg',
            'sharepoint' => 'logo/sharepoint.jpg',
            'automate' => 'logo/automate-logo.jpg',
        ];

        foreach ($slugMap as $key => $file) {
            if (str_contains($slug, $key)) {
                return asset('storage/'.$file);
            }
        }

        return null;
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_technology');
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class, 'experience_technology');
    }
}
