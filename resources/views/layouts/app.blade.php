<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portofolio Professional')</title>
    <link rel="icon" type="image/png" href="{{ !empty($profile->photo_path) ? asset('storage/' . $profile->photo_path) : asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ !empty($profile->photo_path) ? asset('storage/' . $profile->photo_path) : asset('favicon.png') }}">
    <meta name="description" content="@yield('meta_description', $settings['site_description'] ?? 'Portofolio individu web developer')">
    <meta name="keywords" content="@yield('meta_keywords', $settings['meta_keywords'] ?? 'laravel, developer, portfolio')">
    <meta name="author" content="{{ $settings['author_name'] ?? 'M. Rofiq Aulia' }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <!-- Sticky Pill Navbar -->
    <header class="pill-navbar-container">
        <div class="container d-flex justify-content-center">
            <nav class="pill-navbar navbar navbar-expand-lg">
                <a class="navbar-brand fw-bold me-4 d-flex align-items-center gap-2" href="{{ route('portfolio.home') }}">
                    <span class="badge bg-primary text-white rounded-circle p-2 fs-6">
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
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#magang">Magang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#organisasi">Organisasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill {{ request()->routeIs('portfolio.documentations.*') ? 'bg-primary text-white' : '' }}" href="{{ route('portfolio.documentations.index') }}">
                                <i class="bi bi-folder-fill me-1"></i> Dokumentasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-pill" href="{{ route('portfolio.home') }}#kontak">Kontak</a>
                        </li>
                    </ul>
                    <div class="navbar-actions d-flex flex-wrap align-items-center justify-content-center gap-2 ms-lg-3">
                        @if(!empty($profile->cv_path))
                            <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="btn-genz btn-genz-yellow py-1 px-3 fs-6">
                                <i class="bi bi-file-earmark-arrow-down"></i> Unduh CV
                            </a>
                        @endif
                        <a href="{{ route('portfolio.home') }}#kontak" class="btn-genz btn-genz-primary py-1 px-3 fs-6">
                            Hire Me <i class="bi bi-arrow-up-right"></i>
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
                <div class="alert card-genz d-flex align-items-center gap-3 p-3 bg-white" role="alert">
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
    <footer class="footer-genz py-5 mt-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                        <span class="badge bg-primary text-white rounded-circle p-2">
                            <i class="bi bi-code-slash"></i>
                        </span>
                        <h4 class="mb-0 font-heading">{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</h4>
                    </div>
                    <p class="text-muted mb-0 small">
                        {{ $settings['footer_copyright'] ?? '© 2026 M. Rofiq Aulia. All rights reserved.' }}
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-2">
                        @if(isset($profile) && $profile->socialLinks)
                            @foreach($profile->socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary p-2" title="{{ $link->platform }}" style="width: 44px; height: 44px;">
                                    <i class="bi {{ $link->icon ?: 'bi-link-45deg' }} fs-5"></i>
                                </a>
                            @endforeach
                        @endif
                        <a href="{{ url('/admin') }}" class="btn-genz btn-genz-secondary px-3 py-1 fs-6" title="Panel Admin">
                            <i class="bi bi-lock"></i> Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @include('portfolio.partials.image-modal')
</body>
</html>
