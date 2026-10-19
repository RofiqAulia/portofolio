@extends('layouts.app')

@section('title', 'Portofolio Professional')

@section('content')
<div class="container">

    <!-- 1. HERO SECTION -->
    <section id="tentang" class="py-5">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @if($profile && $profile->is_open_to_work)
                        <span class="sticker sticker-yellow">
                            <i class="bi bi-stars"></i> OPEN TO WORK
                        </span>
                    @endif
                    <span class="sticker sticker-blue">
                        <i class="bi bi-geo-alt-fill"></i> {{ $profile->location ?? 'Jakarta, ID' }}
                    </span>
                </div>

                <h1 class="hero-title mb-4">
                    Membangun Website <span class="hero-highlight">Tangguh</span>, Cepat & Berstandar OOP.
                </h1>

                <p class="fs-5 text-muted mb-4 pe-lg-4">
                    {{ $profile->tagline ?? 'Senior Software Engineer dengan spesialisasi arsitektur Laravel, database MySQL teroptimasi, dan frontend responsif.' }}
                </p>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="#kontak" class="btn-genz btn-genz-primary">
                        Hire Me <i class="bi bi-chat-dots-fill"></i>
                    </a>
                    <a href="#proyek" class="btn-genz btn-genz-secondary">
                        Lihat Portofolio <i class="bi bi-arrow-down"></i>
                    </a>
                </div>

                @if($profile && $profile->bio)
                    <div class="p-3 card-genz-blue mt-3">
                        <p class="mb-0 fs-6">
                            <strong>Sekilas Tentang Saya:</strong> {{ $profile->bio }}
                        </p>
                    </div>
                @endif
            </div>

            <div class="col-lg-5 text-center">
                <div class="position-relative d-inline-block">
                    <div class="card-genz p-2 bg-white" style="max-width: 380px;">
                        @if(!empty($profile->photo_path))
                            <div class="project-thumbnail-box position-relative overflow-hidden rounded-4">
                                <img src="{{ asset('storage/' . $profile->photo_path) }}" 
                                     alt="{{ $profile->full_name }}" 
                                     class="img-fluid rounded-4 w-100 object-fit-cover project-thumbnail-img" 
                                     style="height: 380px;"
                                     role="button"
                                     tabindex="0"
                                     aria-label="Lihat foto profil {{ $profile->full_name }}"
                                     data-lightbox-trigger
                                     data-image-src="{{ asset('storage/' . $profile->photo_path) }}"
                                     data-image-title="{{ $profile->full_name }}"
                                     data-image-desc="{{ $profile->headline ?? 'Foto Profil' }}"
                                     data-image-download="{{ asset('storage/' . $profile->photo_path) }}"
                                     data-image-filename="{{ Str::slug($profile->full_name) }}-profile">
                                <div class="project-thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" 
                                            class="btn-genz btn-genz-primary py-1 px-3 fs-6 btn-lightbox-open"
                                            data-image-src="{{ asset('storage/' . $profile->photo_path) }}"
                                            data-image-title="{{ $profile->full_name }}"
                                            data-image-desc="{{ $profile->headline ?? 'Foto Profil' }}"
                                            data-image-download="{{ asset('storage/' . $profile->photo_path) }}"
                                            data-image-filename="{{ Str::slug($profile->full_name) }}-profile">
                                        <i class="bi bi-eye-fill"></i> View
                                    </button>
                                    <a href="{{ asset('storage/' . $profile->photo_path) }}" 
                                       download="{{ Str::slug($profile->full_name) }}-profile" 
                                       class="btn-genz btn-genz-yellow py-1 px-3 fs-6">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center bg-light rounded-4 w-100" style="height: 380px; max-width: 340px;">
                                <i class="bi bi-person-bounding-box text-muted display-1 mb-3"></i>
                                <span class="font-heading fs-5">{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</span>
                            </div>
                        @endif
                        <div class="p-3 text-start">
                            <h4 class="mb-1">{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</h4>
                            <!-- <p class="text-muted small mb-0">{{ $profile->headline ?? 'Senior Software Engineer' }}</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. STRIP TEKNOLOGI -->
    <section class="py-4 my-3">
        <div class="card-genz p-4 bg-white">
            <div class="row align-items-center gy-3">
                <div class="col-md-3 text-center text-md-start">
                    <span class="section-pill mb-1">TECH STACK</span>
                    <h5 class="mb-0 font-heading">Teknologi Andalan</h5>
                </div>
                <div class="col-md-9">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        @forelse($technologies as $tech)
                            <span class="pill-tag">
                                @if($tech->icon_path)
                                    <img src="{{ asset('storage/' . $tech->icon_path) }}" alt="{{ $tech->name }}" width="16" height="16" class="me-1">
                                @else
                                    <i class="bi bi-cpu me-1 text-primary"></i>
                                @endif
                                {{ $tech->name }}
                            </span>
                        @empty
                            <span class="pill-tag">Laravel</span>
                            <span class="pill-tag">PHP 8.3</span>
                            <span class="pill-tag">MySQL</span>
                            <span class="pill-tag">Bootstrap 5</span>
                            <span class="pill-tag">Filament</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. KARTU STATISTIK -->
    <section class="py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-genz p-4 h-100 text-center bg-white">
                    <div class="display-4 font-heading text-primary mb-2">{{ $projects->count() }}+</div>
                    <h5 class="font-heading mb-1">Proyek Terbit</h5>
                    <p class="text-muted small mb-0">Solusi web full-stack siap produksi dengan dokumentasi lengkap.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-genz p-4 h-100 text-center bg-white">
                    <div class="display-4 font-heading text-primary mb-2">{{ $internships->count() }}+</div>
                    <h5 class="font-heading mb-1">Pengalaman Magang</h5>
                    <p class="text-muted small mb-0">Kontribusi profesional dalam lingkungan industri dan pengembangan riil.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-genz p-4 h-100 text-center bg-white">
                    <div class="display-4 font-heading text-primary mb-2">{{ $organizations->count() }}+</div>
                    <h5 class="font-heading mb-1">Pengalaman Organisasi</h5>
                    <p class="text-muted small mb-0">Rekam jejak kepemimpinan, kepengurusan, dan kolaborasi tim teknologi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. UNGKAPAN (QUOTE BANNER) -->
    @if(!empty($profile->quote_text))
        <section class="py-4">
            <div class="quote-banner p-4 p-md-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-quote display-3 text-primary"></i>
                </div>
                <blockquote class="blockquote mb-3">
                    <p class="fs-3 font-heading text-dark mb-3">
                        “{{ $profile->quote_text }}”
                    </p>
                    @if(!empty($profile->quote_author))
                        <footer class="blockquote-footer fs-6 text-dark fw-bold">
                            {{ $profile->quote_author }}
                        </footer>
                    @endif
                </blockquote>
            </div>
        </section>
    @endif

    <!-- 5. SKILL SECTION -->
    <section id="keahlian" class="py-5">
        <div class="text-center mb-5">
            <span class="section-pill">KEAHLIAN & KOMPETENSI</span>
            <h2 class="display-5 font-heading">Keahlian Teruji</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Kombinasi kemampuan rekayasa backend, pengolahan database performa tinggi, serta kepemimpinan dan analisis desain sistem.
            </p>
        </div>

        <div class="row g-4">
            <!-- Hard Skill with Progress Bar -->
            <div class="col-lg-6">
                <div class="card-genz p-4 h-100 bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-2 fs-6">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </span>
                            <h4 class="mb-0 font-heading">{{ $hardSkillCategory->name ?? 'Hard Skills' }}</h4>
                        </div>
                        @if($hardSkillCategory && $hardSkillCategory->skills->count() > 7)
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#hardSkillsCarousel" data-bs-slide="prev" style="width: 36px; height: 36px;" aria-label="Sebelumnya" title="Sebelumnya">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span class="badge bg-white text-muted border border-2 border-dark rounded-pill px-2 py-1 small">
                                    <span class="carousel-counter-current fw-bold text-primary">1</span>/{{ $hardSkillCategory->skills->chunk(7)->count() }}
                                </span>
                                <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#hardSkillsCarousel" data-bs-slide="next" style="width: 36px; height: 36px;" aria-label="Selanjutnya" title="Selanjutnya">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    @if(!$hardSkillCategory || $hardSkillCategory->skills->isEmpty())
                        <p class="text-muted">Skill belum ditambahkan.</p>
                    @elseif($hardSkillCategory->skills->count() <= 7)
                        <div class="vstack gap-4 flex-grow-1">
                            @foreach($hardSkillCategory->skills as $skill)
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold fs-6">{{ $skill->name }}</span>
                                        <span class="badge bg-light text-dark border border-2 border-dark rounded-pill fw-bold">
                                            {{ $skill->level_label ?: $skill->level . '%' }}
                                        </span>
                                    </div>
                                    <div class="progress-genz">
                                        <div class="progress-bar-genz" style="width: {{ $skill->level ?? 80 }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Carousel if > 7 skills -->
                        <div id="hardSkillsCarousel" class="carousel slide carousel-genz carousel-swipeable flex-grow-1 d-flex flex-column" data-bs-ride="false" data-bs-touch="true">
                            <div class="carousel-inner flex-grow-1">
                                @foreach($hardSkillCategory->skills->chunk(7) as $chunkIndex => $chunk)
                                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                        <div class="vstack gap-4">
                                            @foreach($chunk as $skill)
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="fw-bold fs-6">{{ $skill->name }}</span>
                                                        <span class="badge bg-light text-dark border border-2 border-dark rounded-pill fw-bold">
                                                            {{ $skill->level_label ?: $skill->level . '%' }}
                                                        </span>
                                                    </div>
                                                    <div class="progress-genz">
                                                        <div class="progress-bar-genz" style="width: {{ $skill->level ?? 80 }}%;"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Bottom Indicators & Hint -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 pt-3 border-top border-2 border-light">
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left-right text-primary me-1"></i> Geser untuk melihat lainnya
                                </span>
                                <div class="carousel-indicators-genz m-0">
                                    @foreach($hardSkillCategory->skills->chunk(7) as $chunkIndex => $chunk)
                                        <button type="button" class="carousel-indicator-pill {{ $chunkIndex === 0 ? 'active' : '' }}" data-bs-target="#hardSkillsCarousel" data-bs-slide-to="{{ $chunkIndex }}" aria-label="Halaman {{ $chunkIndex + 1 }}"></button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Soft Skill and Analysis (Stickers) -->
            <div class="col-lg-6">
                <div class="vstack gap-4 h-100">
                    <!-- Soft Skills -->
                    <div class="card-genz p-4 bg-white flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success text-white rounded-circle p-2 fs-6">
                                    <i class="bi bi-people-fill"></i>
                                </span>
                                <h4 class="mb-0 font-heading">{{ $softSkillCategory->name ?? 'Soft Skills' }}</h4>
                            </div>
                            @if($softSkillCategory && $softSkillCategory->skills->count() > 7)
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#softSkillsCarousel" data-bs-slide="prev" style="width: 32px; height: 32px;" aria-label="Sebelumnya" title="Sebelumnya">
                                        <i class="bi bi-chevron-left small"></i>
                                    </button>
                                    <span class="badge bg-white text-muted border border-2 border-dark rounded-pill px-2 py-1 small">
                                        <span class="carousel-counter-current fw-bold text-success">1</span>/{{ $softSkillCategory->skills->chunk(7)->count() }}
                                    </span>
                                    <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#softSkillsCarousel" data-bs-slide="next" style="width: 32px; height: 32px;" aria-label="Selanjutnya" title="Selanjutnya">
                                        <i class="bi bi-chevron-right small"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if(!$softSkillCategory || $softSkillCategory->skills->isEmpty())
                            <p class="text-muted">Skill belum ditambahkan.</p>
                        @elseif($softSkillCategory->skills->count() <= 7)
                            <div class="d-flex flex-wrap gap-2 pt-2 flex-grow-1 align-content-start">
                                @foreach($softSkillCategory->skills as $skill)
                                    <span class="sticker sticker-yellow">
                                        <i class="bi bi-check2-circle"></i> {{ $skill->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div id="softSkillsCarousel" class="carousel slide carousel-genz carousel-swipeable flex-grow-1 d-flex flex-column" data-bs-ride="false" data-bs-touch="true">
                                <div class="carousel-inner flex-grow-1">
                                    @foreach($softSkillCategory->skills->chunk(7) as $chunkIndex => $chunk)
                                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                            <div class="d-flex flex-wrap gap-2 pt-2 align-content-start">
                                                @foreach($chunk as $skill)
                                                    <span class="sticker sticker-yellow">
                                                        <i class="bi bi-check2-circle"></i> {{ $skill->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-2 border-top border-2 border-light">
                                    <span class="text-muted small">
                                        <i class="bi bi-arrow-left-right text-success me-1"></i> Geser untuk lainnya
                                    </span>
                                    <div class="carousel-indicators-genz m-0">
                                        @foreach($softSkillCategory->skills->chunk(7) as $chunkIndex => $chunk)
                                            <button type="button" class="carousel-indicator-pill {{ $chunkIndex === 0 ? 'active' : '' }}" data-bs-target="#softSkillsCarousel" data-bs-slide-to="{{ $chunkIndex }}" aria-label="Halaman {{ $chunkIndex + 1 }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Analysis & Design -->
                    <div class="card-genz p-4 bg-white flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </span>
                                <h4 class="mb-0 font-heading">{{ $analysisSkillCategory->name ?? 'Analisis & Desain Sistem' }}</h4>
                            </div>
                            @if($analysisSkillCategory && $analysisSkillCategory->skills->count() > 7)
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#analysisSkillsCarousel" data-bs-slide="prev" style="width: 32px; height: 32px;" aria-label="Sebelumnya" title="Sebelumnya">
                                        <i class="bi bi-chevron-left small"></i>
                                    </button>
                                    <span class="badge bg-white text-muted border border-2 border-dark rounded-pill px-2 py-1 small">
                                        <span class="carousel-counter-current fw-bold text-warning">1</span>/{{ $analysisSkillCategory->skills->chunk(7)->count() }}
                                    </span>
                                    <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#analysisSkillsCarousel" data-bs-slide="next" style="width: 32px; height: 32px;" aria-label="Selanjutnya" title="Selanjutnya">
                                        <i class="bi bi-chevron-right small"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if(!$analysisSkillCategory || $analysisSkillCategory->skills->isEmpty())
                            <p class="text-muted">Skill belum ditambahkan.</p>
                        @elseif($analysisSkillCategory->skills->count() <= 7)
                            <div class="d-flex flex-wrap gap-2 pt-2 flex-grow-1 align-content-start">
                                @foreach($analysisSkillCategory->skills as $skill)
                                    <span class="sticker sticker-blue">
                                        <i class="bi bi-bounding-box"></i> {{ $skill->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div id="analysisSkillsCarousel" class="carousel slide carousel-genz carousel-swipeable flex-grow-1 d-flex flex-column" data-bs-ride="false" data-bs-touch="true">
                                <div class="carousel-inner flex-grow-1">
                                    @foreach($analysisSkillCategory->skills->chunk(7) as $chunkIndex => $chunk)
                                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                            <div class="d-flex flex-wrap gap-2 pt-2 align-content-start">
                                                @foreach($chunk as $skill)
                                                    <span class="sticker sticker-blue">
                                                        <i class="bi bi-bounding-box"></i> {{ $skill->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-2 border-top border-2 border-light">
                                    <span class="text-muted small">
                                        <i class="bi bi-arrow-left-right text-warning me-1"></i> Geser untuk lainnya
                                    </span>
                                    <div class="carousel-indicators-genz m-0">
                                        @foreach($analysisSkillCategory->skills->chunk(7) as $chunkIndex => $chunk)
                                            <button type="button" class="carousel-indicator-pill {{ $chunkIndex === 0 ? 'active' : '' }}" data-bs-target="#analysisSkillsCarousel" data-bs-slide-to="{{ $chunkIndex }}" aria-label="Halaman {{ $chunkIndex + 1 }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Gen-Z Documentation & Blueprint Vault Callout -->
                        <div class="blueprint-vault-card p-3 rounded-4 mt-auto border border-2 border-dark position-relative overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <span class="sticker sticker-yellow py-0 px-2 fw-bold" style="font-size: 0.72rem; transform: rotate(-1deg);">
                                    <i class="bi bi-stars"></i> REAL ARTIFACTS
                                </span>
                                <span class="badge bg-white text-dark border border-dark rounded-pill px-2 py-1 small fw-bold">
                                    <i class="bi bi-folder2-open text-primary"></i> BLUEPRINT LAB
                                </span>
                            </div>
                            <h5 class="font-heading fs-6 mb-1 text-dark">
                                Intip Blueprint, ERD & Alur Sistem Riil ⚡
                            </h5>
                            <p class="text-muted small mb-3 leading-tight" style="font-size: 0.83rem;">
                                Bukan cuma teori! Buka brankas dokumentasi arsitektur: diagram ERD 3NF, flowchart UML, dan berkas teknis yang bisa di-view & di-unduh.
                            </p>
                            <a href="{{ route('portfolio.documentations.index') }}" class="btn-genz btn-genz-yellow w-100 py-2 fs-6 fw-bold shadow-sm">
                                <span>Buka Arsip Dokumentasi & Blueprint</span>
                                <i class="bi bi-arrow-right-short fs-5"></i>
                            </a>
                            <div class="d-flex flex-wrap gap-1 mt-2 pt-2 border-top border-dark border-opacity-10 justify-content-center">
                                <span class="badge bg-white text-dark border border-dark rounded-pill px-2 py-1" style="font-size: 0.68rem;">
                                    🖼️ High-Res Diagram
                                </span>
                                <span class="badge bg-white text-dark border border-dark rounded-pill px-2 py-1" style="font-size: 0.68rem;">
                                    📄 Berkas PDF / Docs
                                </span>
                                <span class="badge bg-white text-dark border border-dark rounded-pill px-2 py-1" style="font-size: 0.68rem;">
                                    ⚡ Free Download
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. PENGALAMAN PROYEK -->
    <section id="proyek" class="py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="section-pill">PORTFOLIO</span>
                <h2 class="display-5 font-heading mb-0">Pengalaman Proyek</h2>
            </div>
            <p class="text-muted mt-2 mt-md-0 mb-0">Hanya menampilkan proyek yang telah terbit & terverifikasi.</p>
        </div>

        @if($projects->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Belum ada proyek yang diterbitkan.</p>
                </div>
            </div>
        @else
            <div class="position-relative project-slider-container">
                <!-- Desktop Side Nav Buttons (floating) -->
                <button class="carousel-nav-btn project-nav-prev d-none d-xl-flex" type="button" aria-label="Proyek Sebelumnya" title="Sebelumnya">
                    <i class="bi bi-chevron-left fs-5"></i>
                </button>
                <button class="carousel-nav-btn project-nav-next d-none d-xl-flex" type="button" aria-label="Proyek Selanjutnya" title="Selanjutnya">
                    <i class="bi bi-chevron-right fs-5"></i>
                </button>

                <!-- Slider Track Wrapper -->
                <div class="project-slider-wrapper">
                    <div class="project-slider-track" id="projectSliderTrack" tabindex="0" role="region" aria-label="Daftar Proyek">
                        @foreach($projects as $index => $project)
                            <div class="project-slide-item" data-index="{{ $index }}">
                                @include('portfolio.partials.project-card', ['project' => $project])
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bottom Controls: Arrows & Indicators -->
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mt-4 pt-2">
                    <button class="btn-genz btn-genz-secondary p-0 rounded-circle project-btn-prev" type="button" style="width: 44px; height: 44px;" aria-label="Sebelumnya" title="Sebelumnya">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </button>

                    <div class="carousel-indicators-genz m-0" id="projectIndicators">
                        @foreach($projects as $index => $project)
                            <button type="button" class="carousel-indicator-pill project-indicator-pill {{ $index === 0 ? 'active' : '' }}" data-slide-to="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>

                    <button class="btn-genz btn-genz-secondary p-0 rounded-circle project-btn-next" type="button" style="width: 44px; height: 44px;" aria-label="Selanjutnya" title="Selanjutnya">
                        <i class="bi bi-arrow-right fs-5"></i>
                    </button>
                </div>

                <!-- Slide Hint Badge with Dynamic Counter -->
                <div class="text-center mt-2 project-slider-hint">
                    <span class="badge bg-white text-muted border border-2 border-dark rounded-pill px-3 py-1 small">
                        <i class="bi bi-arrow-left-right text-primary me-1"></i> Geser untuk melihat lainnya (<span id="projectCounterCurrent" class="text-primary fw-bold">1-2</span> dari {{ $projects->count() }})
                    </span>
                </div>
            </div>
        @endif
    </section>

    <!-- 7. MAGANG (INTERNSHIP) -->
    <section id="magang" class="py-5">
        <div class="text-center mb-5">
            <span class="section-pill">EXPERIENCE</span>
            <h2 class="display-5 font-heading">Pengalaman Magang</h2>
            <p class="text-muted">Kontribusi profesional dalam lingkungan industri nyata.</p>
        </div>

        @if($internships->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada data magang.</p>
                </div>
            </div>
        @elseif($internships->count() === 1)
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @include('portfolio.partials.experience-card', ['experience' => $internships->first(), 'badgeType' => 'magang'])
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-10 position-relative carousel-container">
                    <!-- Desktop Side Nav Buttons -->
                    <button class="carousel-nav-btn carousel-nav-prev d-none d-xl-flex" type="button" data-bs-target="#internshipCarousel" data-bs-slide="prev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left fs-5"></i>
                    </button>
                    <button class="carousel-nav-btn carousel-nav-next d-none d-xl-flex" type="button" data-bs-target="#internshipCarousel" data-bs-slide="next" aria-label="Selanjutnya">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </button>

                    <div id="internshipCarousel" class="carousel slide carousel-genz carousel-swipeable" data-bs-ride="false" data-bs-touch="true">
                        <div class="carousel-inner">
                            @foreach($internships as $index => $intern)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @include('portfolio.partials.experience-card', ['experience' => $intern, 'badgeType' => 'magang'])
                                </div>
                            @endforeach
                        </div>

                        <!-- Bottom Controls: Arrows & Indicators -->
                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mt-4 pt-2">
                            <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#internshipCarousel" data-bs-slide="prev" style="width: 44px; height: 44px;" aria-label="Sebelumnya" title="Sebelumnya">
                                <i class="bi bi-arrow-left fs-5"></i>
                            </button>

                            <div class="carousel-indicators-genz m-0">
                                @foreach($internships as $index => $intern)
                                    <button type="button" class="carousel-indicator-pill {{ $index === 0 ? 'active' : '' }}" data-bs-target="#internshipCarousel" data-bs-slide-to="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>

                            <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#internshipCarousel" data-bs-slide="next" style="width: 44px; height: 44px;" aria-label="Selanjutnya" title="Selanjutnya">
                                <i class="bi bi-arrow-right fs-5"></i>
                            </button>
                        </div>

                        <div class="text-center mt-2">
                            <span class="badge bg-white text-muted border border-2 border-dark rounded-pill px-3 py-1 small">
                                <i class="bi bi-arrow-left-right text-primary me-1"></i> Bisa digeser untuk melihat lainnya (<span class="carousel-counter-current text-primary fw-bold">1</span> dari {{ $internships->count() }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- 8. ORGANISASI (ORGANIZATION) -->
    <section id="organisasi" class="py-5">
        <div class="text-center mb-5">
            <span class="section-pill">LEADERSHIP</span>
            <h2 class="display-5 font-heading">Pengalaman Organisasi</h2>
            <p class="text-muted">Aktivitas organisasi kemahasiswaan, komunitas, dan kepengurusan teknologi.</p>
        </div>

        @if($organizations->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada data organisasi.</p>
                </div>
            </div>
        @elseif($organizations->count() === 1)
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @include('portfolio.partials.experience-card', ['experience' => $organizations->first(), 'badgeType' => 'organisasi'])
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-10 position-relative carousel-container">
                    <!-- Desktop Side Nav Buttons -->
                    <button class="carousel-nav-btn carousel-nav-prev d-none d-xl-flex" type="button" data-bs-target="#organizationCarousel" data-bs-slide="prev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left fs-5"></i>
                    </button>
                    <button class="carousel-nav-btn carousel-nav-next d-none d-xl-flex" type="button" data-bs-target="#organizationCarousel" data-bs-slide="next" aria-label="Selanjutnya">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </button>

                    <div id="organizationCarousel" class="carousel slide carousel-genz carousel-swipeable" data-bs-ride="false" data-bs-touch="true">
                        <div class="carousel-inner">
                            @foreach($organizations as $index => $org)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @include('portfolio.partials.experience-card', ['experience' => $org, 'badgeType' => 'organisasi'])
                                </div>
                            @endforeach
                        </div>

                        <!-- Bottom Controls: Arrows & Indicators -->
                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mt-4 pt-2">
                            <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#organizationCarousel" data-bs-slide="prev" style="width: 44px; height: 44px;" aria-label="Sebelumnya" title="Sebelumnya">
                                <i class="bi bi-arrow-left fs-5"></i>
                            </button>

                            <div class="carousel-indicators-genz m-0">
                                @foreach($organizations as $index => $org)
                                    <button type="button" class="carousel-indicator-pill {{ $index === 0 ? 'active' : '' }}" data-bs-target="#organizationCarousel" data-bs-slide-to="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>

                            <button class="btn-genz btn-genz-secondary p-0 rounded-circle" type="button" data-bs-target="#organizationCarousel" data-bs-slide="next" style="width: 44px; height: 44px;" aria-label="Selanjutnya" title="Selanjutnya">
                                <i class="bi bi-arrow-right fs-5"></i>
                            </button>
                        </div>

                        <div class="text-center mt-2">
                            <span class="badge bg-white text-muted border border-2 border-dark rounded-pill px-3 py-1 small">
                                <i class="bi bi-arrow-left-right text-primary me-1"></i> Bisa digeser untuk melihat lainnya (<span class="carousel-counter-current text-primary fw-bold">1</span> dari {{ $organizations->count() }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- 9. KONTAK (FORM TO MESSAGES) -->
    <section id="kontak" class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-genz p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <span class="section-pill">HUBUNGI SAYA</span>
                        <h2 class="display-5 font-heading">Kirimkan Pesan</h2>
                        <p class="text-muted">Punya tawaran proyek, peluang kolaborasi, atau sekadar ingin berdiskusi? Tinggalkan pesan Anda.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert card-genz bg-danger bg-opacity-10 border-danger p-3 mb-4">
                            <strong class="font-heading text-danger d-block mb-1">Periksa kembali data Anda:</strong>
                            <ul class="mb-0 text-danger ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('portfolio.contact.send') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
                        @csrf

                        <!-- Honeypot anti-spam (invisible field) -->
                        <div style="display: none !important;">
                            <label for="company_website">Website</label>
                            <input type="text" name="company_website" id="company_website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold small text-uppercase">Nama Anda *</label>
                                <input type="text" name="name" id="name" class="form-control rounded-pill border-2 border-dark py-2 px-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Budi Santoso" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold small text-uppercase">Alamat Email *</label>
                                <input type="email" name="email" id="email" class="form-control rounded-pill border-2 border-dark py-2 px-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="budi@perusahaan.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="form-label fw-bold small text-uppercase">Subjek (Opsional)</label>
                            <input type="text" name="subject" id="subject" class="form-control rounded-pill border-2 border-dark py-2 px-3" value="{{ old('subject') }}" placeholder="Diskusi Proyek Web Enterprise">
                        </div>

                        <div>
                            <label for="body" class="form-label fw-bold small text-uppercase">Isi Pesan *</label>
                            <textarea name="body" id="body" rows="5" class="form-control rounded-4 border-2 border-dark p-3 @error('body') is-invalid @enderror" placeholder="Tuliskan kebutuhan Anda secara detail..." required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="attachment" class="form-label fw-bold small text-uppercase d-flex flex-wrap align-items-center justify-content-between gap-1">
                                <span><i class="bi bi-paperclip me-1"></i> Lampiran Berkas / Gambar (Opsional)</span>
                                <span class="text-muted text-lowercase fw-normal" style="font-size: 0.78rem;">PDF, DOC, ZIP, atau Gambar (Maks. 10 MB)</span>
                            </label>
                            <input type="file" name="attachment" id="attachment" class="form-control rounded-pill border-2 border-dark py-2 px-3 @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.csv,image/*">
                            <div class="form-text small text-muted mt-1">
                                <i class="bi bi-info-circle me-1"></i> Mendukung dokumen (PDF, Word, Excel, ZIP) atau semua format gambar (JPG, PNG, WebP, SVG).
                            </div>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" class="btn-genz btn-genz-primary w-100 py-3 fs-5">
                                Kirim Pesan Sekarang <i class="bi bi-send-fill ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
