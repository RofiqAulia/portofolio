@php
    static $techLogoCache = [];
    $name = $tech->name ?? '';
    $slug = strtolower($tech->slug ?? \Illuminate\Support\Str::slug($name));

    if (array_key_exists($slug, $techLogoCache)) {
        $logoUrl = $techLogoCache[$slug];
    } else {
        $logoUrl = null;
        if (is_object($tech) && isset($tech->logo_url)) {
            $logoUrl = $tech->logo_url;
        } elseif (!empty($tech->icon_path)) {
            $logoUrl = asset('storage/' . ltrim($tech->icon_path, '/'));
        } else {
            $possibleFiles = [
                "logo/{$slug}-logo.webp",
                "logo/{$slug}-logo.svg",
                "logo/{$slug}-logo.png",
                "logo/{$slug}-logo.jpg",
                "logo/{$slug}.webp",
                "logo/{$slug}.svg",
                "logo/{$slug}.png",
                "logo/{$slug}.jpg",
            ];
            foreach ($possibleFiles as $file) {
                if (file_exists(public_path('storage/' . $file)) || file_exists(storage_path('app/public/' . $file))) {
                    $logoUrl = asset('storage/' . $file);
                    break;
                }
            }
            if (!$logoUrl) {
                $slugMap = [
                    'laravel' => 'logo/laravel-logo.webp',
                    'php' => 'logo/php-logo.webp',
                    'mysql' => 'logo/mysql-logo.webp',
                    'bootstrap' => 'logo/bootstrap-logo.webp',
                    'tailwind' => 'logo/tailwind-logo.webp',
                    'docker' => 'logo/docker-logo.webp',
                    'git' => 'logo/git-logo.webp',
                    'vue' => 'logo/vue-logo.svg',
                    'react' => 'logo/react-logo.svg',
                    'redis' => 'logo/redis-logo.svg',
                    'notion' => 'logo/notion-logo.webp',
                    'canva' => 'logo/canva-logo.webp',
                    'draw' => 'logo/draw-io-logo.webp',
                    'excel' => 'logo/excel-logo.webp',
                    'word' => 'logo/word-logo.webp',
                    'powerpoint' => 'logo/pptx-logo.webp',
                    'pptx' => 'logo/pptx-logo.webp',
                    'ppt' => 'logo/pptx-logo.webp',
                    'sharepoint' => 'logo/sharepoint.webp',
                    'automate' => 'logo/automate-logo.webp',
                ];
                foreach ($slugMap as $key => $file) {
                    if (str_contains($slug, $key)) {
                        $logoUrl = asset('storage/' . $file);
                        break;
                    }
                }
            }
        }
        $techLogoCache[$slug] = $logoUrl;
    }
@endphp

<div class="tech-brand-item" title="{{ $name }}">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" 
             alt="{{ $name }}" 
             class="tech-brand-logo" 
             width="32" 
             height="32" 
             style="aspect-ratio: 1/1;" 
             loading="lazy" 
             decoding="async">
    @endif
    <span class="tech-brand-name">{{ $name }}</span>
</div>
