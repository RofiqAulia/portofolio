<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portofolio M Rofiq Aulia')</title>
    <!-- Google Fonts Preconnect & Optimized Asynchronous Font Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap">

    @if(!empty($profile->photo_path))
        <!-- Preload LCP Hero Image -->
        <link rel="preload" as="image" href="{{ asset('storage/' . $profile->photo_path) }}" fetchpriority="high">
    @endif

    <!-- Favicon & Mobile Touch Icons (Google Search & Multi-Platform Standard) -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#2F5BFF">

    <meta name="description" content="@yield('meta_description', $settings['site_description'] ?? 'Portofolio M. Rofiq Aulia, Sarjana Terapan Teknik Informatika.')">
    <meta name="keywords" content="@yield('meta_keywords', $settings['meta_keywords'] ?? 'Portofolio Software Engineer, System Analyst Indonesia, Business Analyst Fresh Graduate, Web Developer PHP Laravel')">
    <meta name="author" content="{{ $settings['author_name'] ?? 'M. Rofiq Aulia' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="EAJE6ckcx6X5-_Bbmqbz80tJYYnm-ehc4tep6PeCZ3c">

    <!-- Open Graph / Facebook / LinkedIn / WhatsApp -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Portofolio M Rofiq Aulia')">
    <meta property="og:description" content="@yield('meta_description', $settings['site_description'] ?? 'Portofolio M. Rofiq Aulia, Sarjana Terapan Teknik Informatika.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $profile->full_name ?? 'M. Rofiq Aulia' }}">
    <meta property="og:image" content="{{ !empty($profile->photo_path) ? asset('storage/' . $profile->photo_path) : asset('icon-512.png') }}">
    <meta property="og:image:secure_url" content="{{ !empty($profile->photo_path) ? asset('storage/' . $profile->photo_path) : asset('icon-512.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="500">
    <meta property="og:image:height" content="500">
    <meta property="og:image:alt" content="{{ $profile->full_name ?? 'M. Rofiq Aulia' }} - Foto Profil">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Portofolio Professional')">
    <meta name="twitter:description" content="@yield('meta_description', $settings['site_description'] ?? 'Portofolio M. Rofiq Aulia')">
    <meta name="twitter:image" content="{{ !empty($profile->photo_path) ? asset('storage/' . $profile->photo_path) : asset('icon-512.png') }}">

    <!-- Structured Data (JSON-LD Schema) for Google Rich Snippets & Site Identity -->
    @php
        $photoUrl = !empty($profile->photo_path) ? asset('storage/' . $profile->photo_path) : asset('icon-512.png');
        $schemaData = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'url' => url('/'),
                    'name' => 'Portofolio M Rofiq Aulia',
                    'alternateName' => ['Portofolio M. Rofiq Aulia', 'Rofiq.dev'],
                    'publisher' => [
                        '@id' => url('/') . '#person',
                    ],
                ],
                [
                    '@type' => 'Person',
                    '@id' => url('/') . '#person',
                    'name' => $profile->full_name ?? 'M. Rofiq Aulia',
                    'jobTitle' => $profile->headline ?? 'System Analyst & Software Engineer',
                    'url' => url('/'),
                    'image' => [
                        '@type' => 'ImageObject',
                        'url' => $photoUrl,
                        'caption' => $profile->full_name ?? 'M. Rofiq Aulia',
                    ],
                    'sameAs' => [
                        'https://www.linkedin.com/in/mrofiqaulia/',
                        'https://www.instagram.com/rofiqsyfhdytllah/',
                        'https://github.com/RofiqAulia/',
                    ],
                    'knowsAbout' => [
                        'System Analysis',
                        'Business Analysis',
                        'PHP',
                        'Laravel',
                        'MySQL',
                        'Object-Oriented Programming',
                        'SOLID Principles',
                        'RESTful API',
                        'Continuous Improvement',
                        'Data Analysis',
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <!-- Sticky Pill Navbar -->
    <header class="pill-navbar-container">
        <div class="container d-flex justify-content-center">
            <nav class="pill-navbar navbar navbar-expand-lg">
                <a class="navbar-brand fw-bold me-4 d-flex align-items-center gap-2" href="{{ route('portfolio.home') }}">
                    <span class="inspo-brand-icon">
                        <i class="bi bi-code-slash"></i>
                    </span>
                    <span class="font-heading fs-5">{{ $profile->full_name ?? 'Rofiq.dev' }}</span>
                </a>
                <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse" data-bs-target="#portfolioNav" aria-controls="portfolioNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="portfolioNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center gap-1">
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#tentang">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#keahlian">Skill</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#proyek">Proyek</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#pengalaman">Pengalaman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#sertifikasi">Sertifikasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill {{ request()->routeIs('portfolio.documentations.*') ? 'active' : '' }}" href="{{ route('portfolio.documentations.index') }}">
                                <i class="bi bi-folder-fill me-1"></i> Dokumentasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#kontak">Kontak</a>
                        </li>
                    </ul>
                    <div class="navbar-actions d-flex flex-wrap align-items-center justify-content-center gap-2 ms-lg-3">
                        @if(!empty($profile->cv_path))
                            <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="btn-inspo-outline py-2 px-3 fs-6">
                                <i class="bi bi-file-earmark-arrow-down"></i> Unduh CV
                            </a>
                        @endif
                        <a href="https://wa.me/6282228035426" target="_blank" rel="noopener noreferrer" class="btn-inspo-header py-2 px-3 fs-6 js-wa-btn">
                            Hire Me <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="container mb-4">
                <div class="alert modern-alert d-flex align-items-center gap-3 p-3 bg-white" role="alert">
                    <span class="fs-4 text-success"><i class="bi bi-check-circle-fill"></i></span>
                    <div>
                        <strong class="d-block font-heading">Berhasil Terkirim!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-modern py-5 mt-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                        <span class="inspo-brand-icon">
                            <i class="bi bi-code-slash"></i>
                        </span>
                        <span class="mb-0 font-heading fs-5 fw-bold text-dark">{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</span>
                    </div>
                    <p class="text-muted mb-0 small">
                        {{ $settings['footer_copyright'] ?? '© 2026 M. Rofiq Aulia. All rights reserved.' }}
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-2">
                        @if(isset($profile) && $profile->socialLinks && $profile->socialLinks->isNotEmpty())
                            @foreach($profile->socialLinks as $link)
                                @php
                                    $linkUrl = $link->url;
                                    if (str_contains(strtolower($link->platform), 'github') && ($linkUrl === 'https://github.com' || empty($linkUrl))) {
                                        $linkUrl = 'https://github.com/RofiqAulia/';
                                    } elseif (str_contains(strtolower($link->platform), 'linkedin') && ($linkUrl === 'https://linkedin.com' || empty($linkUrl))) {
                                        $linkUrl = 'https://www.linkedin.com/in/mrofiqaulia/';
                                    } elseif (str_contains(strtolower($link->platform), 'instagram') && ($linkUrl === 'https://instagram.com' || empty($linkUrl))) {
                                        $linkUrl = 'https://www.instagram.com/rofiqsyfhdytllah/';
                                    }
                                @endphp
                                <a href="{{ $linkUrl }}" target="_blank" rel="noopener noreferrer" class="btn-social-icon" title="{{ $link->platform }}">
                                    <i class="bi {{ $link->icon ?: 'bi-link-45deg' }} fs-5"></i>
                                </a>
                            @endforeach
                        @else
                            <a href="https://github.com/RofiqAulia/" target="_blank" rel="noopener noreferrer" class="btn-social-icon" title="GitHub">
                                <i class="bi bi-github fs-5"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/mrofiqaulia/" target="_blank" rel="noopener noreferrer" class="btn-social-icon" title="LinkedIn">
                                <i class="bi bi-linkedin fs-5"></i>
                            </a>
                            <a href="https://www.instagram.com/rofiqsyfhdytllah/" target="_blank" rel="noopener noreferrer" class="btn-social-icon" title="Instagram">
                                <i class="bi bi-instagram fs-5"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @include('portfolio.partials.image-modal')

    @php
        $recaptchaService = app(\App\Services\RecaptchaService::class);
    @endphp

    @if($recaptchaService->isEnabled())
        <script>
            window.recaptchaSiteKey = "{{ $recaptchaService->getSiteKey() }}";
            (function () {
                let recaptchaLoaded = false;
                let recaptchaLoading = false;
                const recaptchaCallbacks = [];

                window.ensureRecaptcha = function (callback) {
                    if (recaptchaLoaded && typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                        callback && callback();
                        return;
                    }
                    if (callback) recaptchaCallbacks.push(callback);
                    if (recaptchaLoading) return;
                    recaptchaLoading = true;

                    const script = document.createElement('script');
                    script.src = "https://www.google.com/recaptcha/api.js?render=" + encodeURIComponent(window.recaptchaSiteKey);
                    script.async = true;
                    script.defer = true;
                    script.onload = function () {
                        if (typeof grecaptcha !== 'undefined') {
                            grecaptcha.ready(function () {
                                recaptchaLoaded = true;
                                while (recaptchaCallbacks.length) {
                                    const cb = recaptchaCallbacks.shift();
                                    try { cb(); } catch (err) { console.error(err); }
                                }
                            });
                        }
                    };
                    script.onerror = function () {
                        recaptchaLoading = false;
                        console.warn('reCAPTCHA script failed to load.');
                        while (recaptchaCallbacks.length) {
                            const cb = recaptchaCallbacks.shift();
                            try { cb(); } catch (err) { console.error(err); }
                        }
                    };
                    document.head.appendChild(script);
                };

                // Lazy load on first user interaction or idle to unblock FCP/LCP/TBT
                const lazyEvents = ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'];
                const triggerLazy = function () {
                    lazyEvents.forEach(evt => window.removeEventListener(evt, triggerLazy, { passive: true }));
                    window.ensureRecaptcha();
                };
                lazyEvents.forEach(evt => window.addEventListener(evt, triggerLazy, { once: true, passive: true }));
                setTimeout(function () {
                    window.ensureRecaptcha();
                }, 4000);
            })();
        </script>
    @else
        <script>
            window.recaptchaSiteKey = null;
            window.ensureRecaptcha = function (cb) { cb && cb(); };
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Global WhatsApp protection with reCAPTCHA v3
            document.addEventListener('click', function (e) {
                const waBtn = e.target.closest('.js-wa-btn');
                if (!waBtn) return;

                if (window.recaptchaSiteKey) {
                    e.preventDefault();
                    const defaultUrl = waBtn.getAttribute('href');
                    const originalContent = waBtn.innerHTML;

                    waBtn.style.pointerEvents = 'none';
                    waBtn.style.opacity = '0.7';

                    window.ensureRecaptcha(function () {
                        if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                            grecaptcha.ready(function () {
                                grecaptcha.execute(window.recaptchaSiteKey, { action: 'whatsapp_click' }).then(function (token) {
                                    fetch("{{ route('portfolio.contact.verify-wa') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ recaptcha_token: token })
                                    })
                                    .then(function (res) { return res.json(); })
                                    .then(function (data) {
                                        waBtn.style.pointerEvents = '';
                                        waBtn.style.opacity = '';
                                        waBtn.innerHTML = originalContent;

                                        if (data.success && data.target_url) {
                                            window.open(data.target_url, '_blank', 'noopener,noreferrer');
                                        } else {
                                            alert(data.message || 'Verifikasi keamanan reCAPTCHA tidak berhasil.');
                                        }
                                    })
                                    .catch(function () {
                                        waBtn.style.pointerEvents = '';
                                        waBtn.style.opacity = '';
                                        waBtn.innerHTML = originalContent;
                                        window.open(defaultUrl, '_blank', 'noopener,noreferrer');
                                    });
                                }).catch(function () {
                                    waBtn.style.pointerEvents = '';
                                    waBtn.style.opacity = '';
                                    waBtn.innerHTML = originalContent;
                                    window.open(defaultUrl, '_blank', 'noopener,noreferrer');
                                });
                            });
                        } else {
                            waBtn.style.pointerEvents = '';
                            waBtn.style.opacity = '';
                            waBtn.innerHTML = originalContent;
                            window.open(defaultUrl, '_blank', 'noopener,noreferrer');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
