@php
    static $techLogoCache = [];
    $name = $tech->name ?? '';
    $slug = strtolower($tech->slug ?? \Illuminate\Support\Str::slug($name));

    if (array_key_exists($slug, $techLogoCache)) {
        $logoUrl = $techLogoCache[$slug];
    } else {
        $logoUrl = null;

        // 1. Prioritaskan gambar asli yang diupload melalui admin panel (icon_path)
        if (!empty($tech->icon_path)) {
            $rawPath = ltrim($tech->icon_path, '/');

            // Cek keberadaan file asli yang diunggah
            if (file_exists(public_path('storage/' . $rawPath)) || file_exists(storage_path('app/public/' . $rawPath))) {
                $logoUrl = asset('storage/' . $rawPath);
            } else {
                // Jika file asli tidak ditemukan dengan ekstensi tersebut (misal .png diganti .svg), cari sibling file
                $dir = pathinfo($rawPath, PATHINFO_DIRNAME);
                $fn = pathinfo($rawPath, PATHINFO_FILENAME);
                $prefix = ($dir && $dir !== '.') ? $dir . '/' : '';
                foreach (['svg', 'png', 'webp', 'jpg', 'jpeg'] as $ext) {
                    $cand = $prefix . $fn . '.' . $ext;
                    if (file_exists(public_path('storage/' . $cand)) || file_exists(storage_path('app/public/' . $cand))) {
                        $logoUrl = asset('storage/' . $cand);
                        break;
                    }
                }
            }
        }

        // 2. Jika model memiliki accessor logo_url
        if (!$logoUrl && is_object($tech) && !empty($tech->logo_url)) {
            $logoUrl = $tech->logo_url;
        }

        // 3. Fallback pencarian file berdasarkan slug / nama
        if (!$logoUrl) {
            $searchBases = [
                "logo/{$slug}-logo",
                "logo/{$slug}",
                "logo/" . \Illuminate\Support\Str::slug($name) . "-logo",
                "logo/" . \Illuminate\Support\Str::slug($name),
            ];
            foreach ($searchBases as $base) {
                foreach (['svg', 'png', 'webp', 'jpg', 'jpeg'] as $ext) {
                    $cand = $base . '.' . $ext;
                    if (file_exists(public_path('storage/' . $cand)) || file_exists(storage_path('app/public/' . $cand))) {
                        $logoUrl = asset('storage/' . $cand);
                        break 2;
                    }
                }
            }
        }

        // 4. Fallback pemetaan SVG resmi jika belum ketemu
        if (!$logoUrl) {
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
                $logoUrl = asset('storage/' . $svgMap[$slug]);
            } else {
                foreach ($svgMap as $key => $file) {
                    if (str_contains($slug, $key)) {
                        $logoUrl = asset('storage/' . $file);
                        break;
                    }
                }
            }
        }

        // 5. Fallback terakhir ke icon_path langsung
        if (!$logoUrl && !empty($tech->icon_path)) {
            $logoUrl = asset('storage/' . ltrim($tech->icon_path, '/'));
        }

        $techLogoCache[$slug] = $logoUrl;
    }
@endphp

<div class="tech-brand-item" title="{{ $name }}" aria-label="{{ $name }}">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" 
             alt="{{ $name }}" 
             class="tech-brand-logo" 
             height="50" 
             loading="lazy" 
             decoding="async">
    @endif
</div>
