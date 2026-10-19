@php
    $slug = strtolower($tech->slug ?? \Illuminate\Support\Str::slug($tech->name ?? ''));
    $name = $tech->name ?? '';
@endphp

<div class="tech-marquee-pill" title="{{ $name }}">
    @if(!empty($tech->icon_path))
        <img src="{{ asset('storage/' . $tech->icon_path) }}" alt="{{ $name }}" class="tech-icon-img" width="22" height="22">
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'laravel'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.84 6.72c-.08-.34-.33-.6-.66-.69l-6.84-1.87a1.01 1.01 0 0 0-.75.09L8.7 7.72 2.7 6.07a1 1 0 0 0-1.02.32c-.22.28-.27.67-.14 1l4.47 11.23c.12.31.39.54.72.6l6.83 1.25c.08.01.16.02.24.02.27 0 .53-.11.72-.3l5.88-5.88c.25-.25.36-.61.3-.96l-1.3-6.63zm-8.23-1.63 5.48 1.5-4.22 2.82-5.4-1.48 4.14-2.84zM3.46 7.6l4.44 1.22-3.1 4.65L3.46 7.6zm5.83 2.83 5.39 1.47-5.07 3.38-5.32-1.45 5-3.4zm-.21 4.7 4.96-3.3 1.03 5.25-4.7 2.06-1.29-4.01zm5.95 2.88-.93-4.73 4.2-2.8 1.05 5.37-4.32 2.16z" fill="#FF2D20"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'php'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#777BB4" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-4.5 13.5H6.2l1.6-7h2.7c1.3 0 2.2.8 2 2-.2 1.3-1.3 2-2.5 2H8.7l-.6 3zm6.1-3.6c.4-.6 1.1-.9 2-.9s1.4.3 1.6.9l.4-1.9h1.3l-1.6 7H18l.7-3.1c-.2.1-.5.1-.8.1-1.3 0-2.2-.8-2-2.1l.2-1zm-4.7-2.4-.7 3h1.3c.7 0 1.2-.4 1.3-1.1.1-.7-.3-1.1-1-1.1l-.9.2z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'mysql'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#00758F" xmlns="http://www.w3.org/2000/svg">
            <path d="M18.8 9.5c-.3-.8-.9-1.5-1.7-1.9-.8-.4-1.8-.4-2.6 0-.8.4-1.3 1.1-1.6 1.9-.3.8-.2 1.8.2 2.6.4.8 1.1 1.3 1.9 1.6.8.3 1.8.2 2.6-.2.8-.4 1.3-1.1 1.6-1.9.4-.8.4-1.8 0-2.6l-.4.5zM12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14.5c-2.5 0-4.5-2-4.5-4.5S10.5 7.5 13 7.5s4.5 2 4.5 4.5-2 4.5-4.5 4.5z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'bootstrap'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#7952B3" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.25 3.75a3 3 0 0 0-2.12-.88H5.87A3 3 0 0 0 2.87 5.87v12.26a3 3 0 0 0 3 3h12.26a3 3 0 0 0 3-3V5.87a3 3 0 0 0-.88-2.12zM9 7.5h3.75a2.25 2.25 0 0 1 1.6 3.83A2.62 2.62 0 0 1 15 13.5a2.63 2.63 0 0 1-2.62 2.63H9V7.5zm2.25 2.25v2.25h1.5a1.13 1.13 0 0 0 0-2.25h-1.5zm0 4.12v2.25h1.88a1.13 1.13 0 0 0 0-2.25H11.25z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'vue'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.5 2H12l-3.5 6.08L5 2H1.5l10.5 18.2L22.5 2h-7z" fill="#35495E"/>
            <path d="M19.13 2H15.5L12 8.08 8.5 2H4.87l7.13 12.38L19.13 2z" fill="#41B883"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'react'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="12" cy="12" rx="4" ry="10" stroke="#00D8FF" stroke-width="1.6" transform="rotate(30 12 12)"/>
            <ellipse cx="12" cy="12" rx="4" ry="10" stroke="#00D8FF" stroke-width="1.6" transform="rotate(90 12 12)"/>
            <ellipse cx="12" cy="12" rx="4" ry="10" stroke="#00D8FF" stroke-width="1.6" transform="rotate(150 12 12)"/>
            <circle cx="12" cy="12" r="2.2" fill="#00D8FF"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'tailwind'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#06B6D4" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.336 6.182 14.975 4.8 12.001 4.8zm-6 7.2c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624 1.177 1.194 2.538 2.576 5.512 2.576 3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C10.336 13.382 8.975 12 6.001 12z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'docker'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#2496ED" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.98 11.08h1.69v1.65h-1.69v-1.65zm-2.25 0h1.69v1.65h-1.69v-1.65zm-2.25 0h1.69v1.65H9.48v-1.65zm-2.25 0h1.69v1.65H7.23v-1.65zm4.5-2.19h1.69v1.65h-1.69V8.89zm-2.25 0h1.69v1.65H9.48V8.89zm-2.25 0h1.69v1.65H7.23V8.89zm4.5-2.19h1.69v1.65h-1.69V6.7zm8.79 6.22c-.41-.29-1.25-.36-1.92-.09-.32-.97-1.12-1.32-1.12-1.32s-.36.75-.08 1.54c-.39.23-1.02.32-1.67.24-.07-.44-.27-.85-.56-1.18l-.13-.13H4.5v3.83c0 2.2 1.8 3.99 4.02 3.99 3.52 0 6.64-1.89 7.9-4.75.95-.08 2.37-.53 2.87-2.13z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'git'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#F05032" xmlns="http://www.w3.org/2000/svg">
            <path d="M21.62 10.62 13.38 2.38a2.38 2.38 0 0 0-3.37 0L7.64 4.75l3.24 3.24a2.82 2.82 0 0 1 3.56 3.56l3.12 3.12a2.82 2.82 0 1 1-1.69 1.68l-2.88-2.88v4.25a2.82 2.82 0 1 1-2.38 0v-4.34a2.83 2.83 0 0 1-1.52-3.71L5.97 6.42 2.38 10a2.38 2.38 0 0 0 0 3.38l8.24 8.24a2.38 2.38 0 0 0 3.37 0l7.63-7.62a2.38 2.38 0 0 0 0-3.38z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'redis'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#DC382D" xmlns="http://www.w3.org/2000/svg">
            <path d="M21.43 14.73 12.8 19.7a1.6 1.6 0 0 1-1.6 0L2.57 14.73a1.6 1.6 0 0 1-.8-1.38V5.38c0-.58.31-1.11.8-1.39L11.2.03a1.6 1.6 0 0 1 1.6 0l8.63 4.96c.49.28.8.81.8 1.39v7.97c0 .57-.31 1.1-.8 1.38zM12 4.41l-5.6 3.22L12 10.85l5.6-3.22L12 4.41z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'filament'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#FFA439" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'python'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#3776AB" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.93 2c-3.1 0-5 1.4-5 3.5v2.5h5v1H4.5c-2.1 0-3.5 1.5-3.5 4s1.4 4 3.5 4h1.5v-2.2c0-2 1.6-3.8 3.8-3.8h5c1.8 0 3.2-1.4 3.2-3.2V5.5c0-2.1-1.9-3.5-6.07-3.5zm-2.43 2c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1zm2.5 18c3.1 0 5-1.4 5-3.5V16h-5v-1h7.43c2.1 0 3.5-1.5 3.5-4s-1.4-4-3.5-4H18v2.2c0 2-1.6 3.8-3.8 3.8h-5c-1.8 0-3.2 1.4-3.2 3.2v2.3c0 2.1 1.9 3.5 6.07 3.5zm2.43-2c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'javascript') || $slug === 'js')
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#F7DF1E" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="4" fill="#F7DF1E"/>
            <path d="M6 18.5V9.5h2v6.5c0 1-.5 1.5-1.5 1.5-.3 0-.5 0-.5-.1v1.1zm8.2-.1c-1.5 0-2.6-.8-2.6-2.3h2c0 .6.4 1 1.1 1 .6 0 1-.3 1-.8 0-.6-.5-.8-1.4-1.2-1.3-.6-2.1-1.2-2.1-2.4 0-1.4 1.1-2.3 2.6-2.3 1.4 0 2.4.7 2.5 2.1h-2c0-.5-.4-.8-.9-.8-.5 0-.8.3-.8.7 0 .5.4.7 1.3 1.1 1.4.6 2.2 1.3 2.2 2.5-.1 1.5-1.2 2.4-2.9 2.4z" fill="#000"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @elseif(str_contains($slug, 'node'))
        <svg class="tech-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="#5FA04E" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2 3.5 7v10L12 22l8.5-5V7L12 2zm0 3.2 5.9 3.5-5.9 3.4-5.9-3.4L12 5.2z"/>
        </svg>
        <span class="tech-name">{{ $name }}</span>
    @else
        {{-- Jika tidak ada logo, cukup tampilkan teks nama teknologi tanpa icon --}}
        <span class="tech-name text-only">{{ $name }}</span>
    @endif
</div>
