@php
    $name = $tech->name ?? '';
    $logoUrl = null;

    if (is_object($tech) && isset($tech->logo_url)) {
        $logoUrl = $tech->logo_url;
    } elseif (!empty($tech->icon_path)) {
        $logoUrl = asset('storage/' . ltrim($tech->icon_path, '/'));
    } else {
        $slug = strtolower($tech->slug ?? \Illuminate\Support\Str::slug($name));
        $possibleFiles = [
            "logo/{$slug}-logo.svg",
            "logo/{$slug}-logo.png",
            "logo/{$slug}-logo.jpg",
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
                'laravel' => 'logo/laravel-logo.jpg',
                'php' => 'logo/php-logo.png',
                'mysql' => 'logo/mysql-logo.png',
                'bootstrap' => 'logo/bootstrap-logo.jpg',
                'tailwind' => 'logo/tailwind-logo.jpg',
                'docker' => 'logo/docker-logo.jpg',
                'git' => 'logo/git-logo.png',
                'vue' => 'logo/vue-logo.svg',
                'react' => 'logo/react-logo.svg',
                'redis' => 'logo/redis-logo.svg',
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
                    $logoUrl = asset('storage/' . $file);
                    break;
                }
            }
        }
    }
@endphp

<div class="tech-brand-item" title="{{ $name }}">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $name }}" class="tech-brand-logo" width="30" height="30" loading="lazy" decoding="async">
    @endif
    <span class="tech-brand-name">{{ $name }}</span>
</div>
