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
            "logo/{$slug}-logo.png",
            "logo/{$slug}-logo.jpg",
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

<div class="tech-marquee-pill" title="{{ $name }}">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $name }}" class="tech-icon-img" width="26" height="26" loading="lazy">
        <span class="tech-name">{{ $name }}</span>
    @else
        {{-- Jika tidak ada logo, cukup tampilkan teks nama keahlian tanpa icon --}}
        <span class="tech-name text-only">{{ $name }}</span>
    @endif
</div>
