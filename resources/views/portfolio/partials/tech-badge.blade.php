@php
    static $techLogoCache = [];
    $name = $tech->name ?? '';
    $slug = strtolower($tech->slug ?? \Illuminate\Support\Str::slug($name));

    if (array_key_exists($slug, $techLogoCache)) {
        $logoUrl = $techLogoCache[$slug];
    } else {
        $logoUrl = null;

        // 1. Direct SVG Mapping for official clean transparent vector logos (no container box)
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

        // 2. If not matched, search for SVG version of icon_path or slug
        if (!$logoUrl) {
            $possibleSvgs = [
                "logo/{$slug}-logo.svg",
                "logo/{$slug}.svg",
            ];
            if (!empty($tech->icon_path)) {
                $base = pathinfo($tech->icon_path, PATHINFO_FILENAME);
                $dir = pathinfo($tech->icon_path, PATHINFO_DIRNAME);
                $possibleSvgs[] = ($dir !== '.' ? $dir . '/' : '') . $base . '.svg';
            }
            foreach ($possibleSvgs as $svgFile) {
                if (file_exists(public_path('storage/' . $svgFile)) || file_exists(storage_path('app/public/' . $svgFile))) {
                    $logoUrl = asset('storage/' . $svgFile);
                    break;
                }
            }
        }

        // 3. Fallback to provided paths only if no SVG is available
        if (!$logoUrl) {
            if (is_object($tech) && !empty($tech->logo_url)) {
                $logoUrl = $tech->logo_url;
            } elseif (!empty($tech->icon_path)) {
                $logoUrl = asset('storage/' . ltrim($tech->icon_path, '/'));
            }
        }

        $techLogoCache[$slug] = $logoUrl;
    }
@endphp

<div class="tech-brand-item" title="{{ $name }}" aria-label="{{ $name }}">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" 
             alt="{{ $name }}" 
             class="tech-brand-logo" 
             height="68" 
             loading="lazy" 
             decoding="async">
    @endif
</div>
