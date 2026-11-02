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
        // 1. Prioritaskan gambar asli yang diupload melalui admin panel (icon_path)
        if (! empty($this->icon_path)) {
            $rawPath = ltrim($this->icon_path, '/');

            // Cek keberadaan berkas asli yang diunggah di storage
            if (file_exists(public_path('storage/'.$rawPath)) || file_exists(storage_path('app/public/'.$rawPath))) {
                return asset('storage/'.$rawPath);
            }

            // Jika berkas asli dengan ekstensi tersebut tidak ditemukan, cari sibling file dengan nama sama
            $dir = pathinfo($rawPath, PATHINFO_DIRNAME);
            $fn = pathinfo($rawPath, PATHINFO_FILENAME);
            $prefix = ($dir && $dir !== '.') ? $dir.'/' : '';
            foreach (['svg', 'png', 'webp', 'jpg', 'jpeg'] as $ext) {
                $cand = $prefix.$fn.'.'.$ext;
                if (file_exists(public_path('storage/'.$cand)) || file_exists(storage_path('app/public/'.$cand))) {
                    return asset('storage/'.$cand);
                }
            }
        }

        $slug = strtolower($this->slug ?? Str::slug($this->name ?? ''));

        // 2. Pencarian file berdasarkan slug / nama
        $searchBases = [
            "logo/{$slug}-logo",
            "logo/{$slug}",
            'logo/'.Str::slug($this->name ?? '').'-logo',
            'logo/'.Str::slug($this->name ?? ''),
        ];
        foreach ($searchBases as $base) {
            foreach (['svg', 'png', 'webp', 'jpg', 'jpeg'] as $ext) {
                $cand = $base.'.'.$ext;
                if (file_exists(public_path('storage/'.$cand)) || file_exists(storage_path('app/public/'.$cand))) {
                    return asset('storage/'.$cand);
                }
            }
        }

        // 3. Fallback pemetaan SVG resmi jika belum ketemu
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

        // 4. Fallback langsung ke icon_path jika disediakan
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
