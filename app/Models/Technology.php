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
        $slug = strtolower($this->slug ?? Str::slug($this->name ?? ''));

        // 1. Direct SVG Mapping for official clean transparent vector logos (zero box/container)
        $svgMap = [
            'laravel' => 'logo/laravel-logo.svg',
            'php' => 'logo/php-logo.svg',
            'mysql' => 'logo/mysql-logo.svg',
            'bootstrap' => 'logo/bootstrap-logo.svg',
            'bootstrap-5' => 'logo/bootstrap-5-logo.svg',
            'tailwind' => 'logo/tailwind-logo.svg',
            'tailwind-css' => 'logo/tailwind-css-logo.svg',
            'docker' => 'logo/docker-logo.svg',
            'git' => 'logo/git-logo.svg',
            'vue' => 'logo/vue-logo.svg',
            'vue-js' => 'logo/vue-logo.svg',
            'react' => 'logo/react-logo.svg',
            'redis' => 'logo/redis-logo.svg',
            'notion' => 'logo/notion-logo.svg',
            'canva' => 'logo/canva-logo.svg',
            'draw-io' => 'logo/draw-io-logo.svg',
            'draw' => 'logo/draw-io-logo.svg',
            'excel' => 'logo/excel-logo.svg',
            'word' => 'logo/word-logo.svg',
            'powerpoint' => 'logo/powerpoint-logo.svg',
            'pptx' => 'logo/pptx-logo.svg',
            'ppt' => 'logo/powerpoint-logo.svg',
            'sharepoint' => 'logo/sharepoint.svg',
            'power-automate' => 'logo/power-automate-logo.svg',
            'automate' => 'logo/automate-logo.svg',
        ];

        if (isset($svgMap[$slug])) {
            return asset('storage/'.$svgMap[$slug]);
        }

        foreach ($svgMap as $key => $file) {
            if (str_contains($slug, $key)) {
                return asset('storage/'.$file);
            }
        }

        // 2. Check if SVG file exists on disk
        $possibleSvgs = [
            "logo/{$slug}-logo.svg",
            "logo/{$slug}.svg",
        ];
        if (! empty($this->icon_path)) {
            $base = pathinfo($this->icon_path, PATHINFO_FILENAME);
            $dir = pathinfo($this->icon_path, PATHINFO_DIRNAME);
            $possibleSvgs[] = ($dir !== '.' ? $dir.'/' : '').$base.'.svg';
        }
        foreach ($possibleSvgs as $svgFile) {
            if (file_exists(public_path('storage/'.$svgFile)) || file_exists(storage_path('app/public/'.$svgFile))) {
                return asset('storage/'.$svgFile);
            }
        }

        // 3. Fallback to icon_path if provided
        if (! empty($this->icon_path)) {
            return asset('storage/'.ltrim($this->icon_path, '/'));
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
