@extends('layouts.app')

@section('title', 'Portofolio M Rofiq Aulia')
@section('meta_description', 'Portofolio M. Rofiq Aulia, Sarjana Terapan Teknik Informatika. Berfokus pada IT Business Analyst, System Analyst, dan Software Engineer berbasis PHP Laravel.')
@section('meta_keywords', 'Portofolio Software Engineer, System Analyst Indonesia, Business Analyst Fresh Graduate, Web Developer PHP Laravel, Teknik Informatika, continuous improvement')

@section('content')
<div class="container">

    <!-- 1. HERO BENTO MASTER STAGE -->
    <section id="tentang" class="py-4 py-lg-5">
        <div class="hero-brand-stage position-relative overflow-hidden">
            <div class="row align-items-center gy-5 position-relative z-2">
                {{-- Left Column: Copy & CTAs --}}
                <div class="col-lg-6 col-xl-7">
                    <!-- Status Badges Cluster -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        @if($profile && $profile->is_open_to_work)
                            <span class="hero-pill-badge hero-pill-available">
                                <span class="status-pulse-dot"></span> OPEN TO WORK
                            </span>
                        @endif
                        <span class="hero-pill-badge hero-pill-location">
                            <i class="bi bi-geo-alt-fill text-danger"></i> {{ $profile->location ?? 'Malang, ID' }}
                        </span>
                        <span class="hero-pill-badge hero-pill-role d-none d-sm-inline-flex">
                            <i class="bi bi-patch-check-fill text-primary"></i> System Analyst &amp; IT Business Analyst, Software Engineer
                        </span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="hero-stage-title mb-3">
                        Siap <span class="hero-stage-highlight">Berkontribusi Nyata</span> <br class="d-none d-md-inline">dalam <span class="hero-stage-highlight-blue">Proyek &amp; Perusahaan</span> Anda
                    </h1>

                    <!-- Subtitle / Tagline -->
                    <p class="hero-stage-desc mb-4 pe-lg-4">
                        {{ $profile->tagline ?? 'Fresh Graduate Sarjana Terapan Teknik Informatika Politeknik Negeri Malang (IPK 3.68/4.00) dengan pengalaman magang serta keterlibatan proyek nyata dalam pengembangan berbasis website seperti Analisis Sistem, Rekayasa Perangkat Lunak, dan Continuous Improvement. Berpengalaman menerjemahkan alur bisnis korporat menjadi arsitektur sistem enterprise yang terstruktur, efisien, dan siap berdampak nyata.' }}
                    </p>

                    <!-- CTAs (Vibrant Bento Pill Buttons) -->
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <a href="https://wa.me/6282228035426" target="_blank" rel="noopener noreferrer" class="btn-hero-purple">
                            <span>Hire Me / Diskusi Proyek</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="#proyek" class="btn-hero-white">
                            <span>Jelajahi Portofolio</span>
                            <i class="bi bi-arrow-down"></i>
                        </a>
                        @if(!empty($profile->cv_path))
                            <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="btn-hero-white d-none d-md-inline-flex">
                                <i class="bi bi-file-earmark-arrow-down text-primary"></i>
                                <span>Unduh CV</span>
                            </a>
                        @endif
                    </div>

                    <!-- Sekilas Tentang Saya (Frosted Glass Bento Mini-Card) -->
                    <div class="hero-stage-bio p-3 p-md-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                <i class="bi bi-person-lines-fill fs-6"></i>
                            </span>
                            <strong class="font-heading fs-6 text-dark">Sekilas Tentang Saya</strong>
                        </div>
                        <p class="mb-0 text-muted small lh-base">
                            {{ $profile->bio ?? 'Memiliki pengalaman serta terlibat nyata dalam proyek pengembangan perangkat lunak end-to-end dan optimasi proses bisnis di lingkungan korporat (Human Capital, Produksi, dan Manufaktur) dibidang digitalisasi. Memiliki rekam jejak dalam mengidentifikasi bottleneck non-produktif, seperti digitalisasi paperless program, serta merekayasa sistem enterprise termasuk sistem manajemen beasiswa terdaftar HKI untuk korporasi industri manufaktur berskala nasional. Menguasai analisis sistem & continuous improvement (BPMN, Flowchart, UI/UX Figma) serta rekayasa database dan backend berbasis PHP Laravel, MySQL, Bootstrap, Git, dan Deployment.' }}
                        </p>
                    </div>
                </div>

                {{-- Right Column: Bento Studio Showcase (Profile & Staged Dev Gear) --}}
                <div class="col-lg-6 col-xl-5 text-center">
                    <div class="hero-studio-stage position-relative d-inline-block w-100">
                        <!-- Profile Card on Bento Surface -->
                        <div class="hero-studio-card position-relative mx-auto">
                            @if(!empty($profile->photo_path))
                                <div class="hero-studio-photo-wrap position-relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $profile->photo_path) }}" 
                                         itemprop="image"
                                         alt="{{ $profile->full_name ?? 'M. Rofiq Aulia' }} - System Analyst & Software Engineer Indonesia" 
                                         class="img-fluid w-100 object-fit-cover hero-studio-photo" 
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
                                                class="btn-inspo-header py-2 px-3 fs-6 btn-lightbox-open"
                                                data-image-src="{{ asset('storage/' . $profile->photo_path) }}"
                                                data-image-title="{{ $profile->full_name }}"
                                                data-image-desc="{{ $profile->headline ?? 'Foto Profil' }}"
                                                data-image-download="{{ asset('storage/' . $profile->photo_path) }}"
                                                data-image-filename="{{ Str::slug($profile->full_name) }}-profile">
                                            <i class="bi bi-eye-fill"></i> View
                                        </button>
                                        <a href="{{ asset('storage/' . $profile->photo_path) }}" 
                                           download="{{ Str::slug($profile->full_name) }}-profile" 
                                           class="btn-inspo-outline bg-white py-2 px-3 fs-6">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center bg-white w-100 shadow-sm" style="height: 380px;">
                                    <i class="bi bi-person-bounding-box text-primary display-1 mb-3 opacity-75"></i>
                                    <span class="font-heading fs-5">{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</span>
                                </div>
                            @endif

                            <div class="p-3 text-start bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h4 class="mb-0 font-heading fs-5 text-dark">{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</h4>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small px-2 py-1">
                                        <i class="bi bi-patch-check-fill text-primary"></i> Verified
                                    </span>
                                </div>
                                <p class="text-muted small mb-0">{{ $profile->headline ?? 'System Analyst & IT Business Analyst, Software Engineer' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Floating Indicator Dots (Bento Slider Visual Anchor) -->
            <div class="hero-stage-dots d-flex justify-content-center align-items-center gap-2 mt-4 pt-2">
                <div class="hero-dots-pill d-inline-flex align-items-center gap-2 px-3 py-1 bg-white bg-opacity-75 shadow-sm rounded-pill">
                    <span class="hero-dot active"></span>
                    <span class="hero-dot"></span>
                    <span class="hero-dot"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. STRIP KEAHLIAN & TECH STACK (INFINITE MARQUEE SEPERTI GAMBAR 2) -->
    <section class="tech-brand-strip" aria-label="Teknologi dan Tools Andalan">
        <div class="tech-brand-header">
            <h2 class="tech-brand-title">Teknologi & Tools Andalan</h2>
        </div>

        @php
            $techList = $technologies->isNotEmpty() ? $technologies : collect([
                (object)['name' => 'Laravel', 'slug' => 'laravel', 'icon_path' => 'logo/laravel-logo.jpg'],
                (object)['name' => 'PHP', 'slug' => 'php', 'icon_path' => 'logo/php-logo.png'],
                (object)['name' => 'MySQL', 'slug' => 'mysql', 'icon_path' => 'logo/mysql-logo.png'],
                (object)['name' => 'Bootstrap 5', 'slug' => 'bootstrap-5', 'icon_path' => 'logo/bootstrap-logo.jpg'],
                (object)['name' => 'Vue.js', 'slug' => 'vue-js', 'icon_path' => 'logo/vue-logo.svg'],
                (object)['name' => 'React', 'slug' => 'react', 'icon_path' => 'logo/react-logo.svg'],
                (object)['name' => 'Tailwind CSS', 'slug' => 'tailwind-css', 'icon_path' => 'logo/tailwind-logo.jpg'],
                (object)['name' => 'Redis', 'slug' => 'redis', 'icon_path' => 'logo/redis-logo.svg'],
                (object)['name' => 'Docker', 'slug' => 'docker', 'icon_path' => 'logo/docker-logo.jpg'],
                (object)['name' => 'Git', 'slug' => 'git', 'icon_path' => 'logo/git-logo.png'],
                (object)['name' => 'Notion', 'slug' => 'notion', 'icon_path' => 'logo/notion-logo.png'],
                (object)['name' => 'Canva', 'slug' => 'canva', 'icon_path' => 'logo/canva-logo.png'],
                (object)['name' => 'Draw.io', 'slug' => 'draw-io', 'icon_path' => 'logo/draw-io-logo.png'],
                (object)['name' => 'Microsoft Excel', 'slug' => 'excel', 'icon_path' => 'logo/excel-logo.jpg'],
                (object)['name' => 'Microsoft Word', 'slug' => 'word', 'icon_path' => 'logo/word-logo.jpg'],
                (object)['name' => 'Microsoft PowerPoint', 'slug' => 'powerpoint', 'icon_path' => 'logo/pptx-logo.jpg'],
                (object)['name' => 'Microsoft SharePoint', 'slug' => 'sharepoint', 'icon_path' => 'logo/sharepoint.jpg'],
                (object)['name' => 'Power Automate', 'slug' => 'power-automate', 'icon_path' => 'logo/automate-logo.jpg'],
            ]);

            $count = $techList->count();
            $repeatCount = $count > 0 ? (int) max(2, (int) ceil(24 / $count)) : 2;
        @endphp

        <div class="tech-brand-marquee">
            <div class="tech-brand-track">
                @for($k = 0; $k < $repeatCount; $k++)
                    @foreach($techList as $tech)
                        @include('portfolio.partials.tech-badge', ['tech' => $tech])
                    @endforeach
                @endfor
            </div>
            <div class="tech-brand-track" aria-hidden="true">
                @for($k = 0; $k < $repeatCount; $k++)
                    @foreach($techList as $tech)
                        @include('portfolio.partials.tech-badge', ['tech' => $tech])
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    <!-- 3. BENTO GRID SHOWCASE (STATISTIK, REKAM JEJAK & FILOSOFI ALA GAMBAR 1) -->
    <section class="py-5 bento-showcase-section" id="overview">
        <!-- Header: Title on Left, Action Button on Right (Ala Gambar 1) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2">
            <div>
                <h2 class="bento-main-title mb-1">Pencapaian Nyata & Rekam Jejak Teruji</h2>
                <p class="text-muted mb-0 small">Kombinasi integritas rekayasa kode, pengalaman industri, dan kepemimpinan sistem.</p>
            </div>
            <a href="#proyek" class="btn-bento-header">
                <span>Jelajahi Portofolio</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- 3-Column Bento Grid (6 Kartu Bergradien Modern) -->
        <div class="row g-4 bento-grid-container">
            <!-- Kolom 1 -->
            <div class="col-lg-4 col-md-6 d-flex flex-column gap-4">
                <!-- Card 1: 6+ Proyek Terbit (Sky Blue Gradient) -->
                <div class="bento-card bento-card-sky bento-card-proyek">
                    <div class="bento-card-content">
                        <span class="bento-badge">PRODUKSI RIIL</span>
                        <div class="bento-number-wrap">
                            <span class="bento-stat-number">{{ $projects->count() }}+</span>
                            <span class="bento-stat-label">Proyek Terbit</span>
                        </div>
                        <p class="bento-desc">
                            Solusi web full-stack siap produksi dengan arsitektur database ternormalisasi & dokumentasi lengkap.
                        </p>
                        <a href="#proyek" class="bento-link">
                            <span>Lihat Daftar Proyek</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                    <!-- Visual Illustration Decoration -->
                    <div class="bento-visual bento-visual-code" aria-hidden="true">
                        <div class="bento-mock-window">
                            <div class="mock-header">
                                <span class="dot dot-red"></span>
                                <span class="dot dot-yellow"></span>
                                <span class="dot dot-green"></span>
                            </div>
                            <div class="mock-body">
                                <code>&lt;FullStack /&gt;</code>
                                <div class="mock-line w-75"></div>
                                <div class="mock-line w-50"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: 3+ Pengalaman Magang (Warm Amber Gradient) -->
                <div class="bento-card bento-card-amber bento-card-magang">
                    <div class="bento-card-content">
                        <span class="bento-badge">PENGALAMAN INDUSTRI</span>
                        <div class="bento-number-wrap">
                            <span class="bento-stat-number">{{ $internships->count() }}+</span>
                            <span class="bento-stat-label">Pengalaman Magang</span>
                        </div>
                        <p class="bento-desc">
                            Kontribusi aktif dan implementasi sistem skala enterprise pada lingkungan korporasi industri manufaktur berskala nasional.
                        </p>
                        <a href="#magang" class="bento-link">
                            <span>Telusuri Jejak Magang</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                    <!-- Visual Icon Decoration -->
                    <div class="bento-visual bento-visual-building" aria-hidden="true">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Kolom 2 -->
            <div class="col-lg-4 col-md-6 d-flex flex-column gap-4">
                <!-- Card 3: Kutipan Filosofi Kode (Ocean Cyan Gradient - Taller) -->
                <div class="bento-card bento-card-cyan bento-card-quote">
                    <div class="bento-card-content">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="bento-badge">ETOS & PRINSIP</span>
                            <i class="bi bi-quote fs-2 opacity-75"></i>
                        </div>
                        <blockquote class="bento-quote-text">
                            “{{ $profile->quote_text ?? 'Memulai dengan Bismillah berakhir Alhamdulillah' }}”
                        </blockquote>
                        <div class="bento-quote-footer mt-auto pt-3">
                            <div class="bento-avatar-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $profile->quote_author ?? 'M. Rofiq Aulia' }}</h6>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Large Quote Mark -->
                    <div class="bento-visual bento-visual-quote-bg" aria-hidden="true">
                        <i class="bi bi-quote"></i>
                    </div>
                </div>

                <!-- Card 4: Blueprint & Dokumentasi (Royal Blue Gradient) -->
                <div class="bento-card bento-card-royal bento-card-blueprint">
                    <div class="bento-card-content">
                        <span class="bento-badge">ARSIP ARTIFAK</span>
                        <h4 class="bento-card-heading">Blueprint & Diagram Lab</h4>
                        <p class="bento-desc">
                            Bukan cuma teori! Buka brankas dokumentasi arsitektur: diagram ERD 3NF, flowchart proses bisnis & UML.
                        </p>
                        <a href="{{ route('portfolio.documentations.index') }}" class="btn-bento-pill">
                            <span>Buka Arsip Blueprint</span>
                            <i class="bi bi-folder2-open"></i>
                        </a>
                    </div>
                    <!-- Visual Blueprint Graph Decoration -->
                    <div class="bento-visual bento-visual-diagram" aria-hidden="true">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Kolom 3 -->
            <div class="col-lg-4 col-md-12 d-flex flex-column gap-4">
                <!-- Card 5: 4+ Pengalaman Organisasi (Violet Purple Gradient) -->
                <div class="bento-card bento-card-purple bento-card-organisasi">
                    <div class="bento-card-content">
                        <span class="bento-badge">KEPEMIMPINAN & TIM</span>
                        <div class="bento-number-wrap">
                            <span class="bento-stat-number">{{ $organizations->count() }}+</span>
                            <span class="bento-stat-label">Pengalaman Organisasi</span>
                        </div>
                        <p class="bento-desc">
                            Rekam jejak kepemimpinan divisi teknologi, manajemen inisiatif, serta kolaborasi solid lintas tim.
                        </p>
                        <a href="#organisasi" class="bento-link">
                            <span>Jejak Kepengurusan</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                    <!-- Visual Team Network Decoration -->
                    <div class="bento-visual bento-visual-team" aria-hidden="true">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>

                <!-- Card 6: Dokumentasi Penghargaan & Sertifikasi (Coral Pink Gradient) -->
                <div class="bento-card bento-card-coral bento-card-sertifikasi">
                    <div class="bento-card-content">
                        <span class="bento-badge">PRESTASI &amp; KREDENSIAL</span>
                        <h4 class="bento-card-heading">Penghargaan &amp; Sertifikasi</h4>
                        <p class="bento-desc">
                            Dokumentasi resmi sertifikasi kompetensi magang industri, lisensi kepemimpinan, dan hak cipta karya (HKI).
                        </p>
                        <a href="#sertifikasi" class="bento-link">
                            <span>Buka Arsip Sertifikasi</span>
                            <i class="bi bi-arrow-down-right"></i>
                        </a>
                    </div>
                    <!-- Visual Award Decoration -->
                    <div class="bento-visual bento-visual-award" aria-hidden="true">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. SKILL SECTION (REKONSEPSI: HARD & SOFT SKILLS DALAM 1 MASTER CONTAINER + BLUEPRINT LAB BULLETPROOF) -->
    <section id="keahlian" class="py-5 inspo-section">
        <div class="text-center mb-5">
            <span class="inspo-section-pill">KEAHLIAN & KOMPETENSI</span>
            <h2 class="inspo-header-title">Keahlian Teruji</h2>
            <p class="inspo-header-subtitle mx-auto" style="max-width: 620px;">
                Kombinasi kemampuan rekayasa backend, pengolahan database performa tinggi, serta kepemimpinan dan analisis desain sistem.
            </p>
        </div>

        <div class="row g-4 align-items-stretch">
            <!-- Kolom 1: Hard Skills & Soft Skills Piramida Diagram (Tanpa Box Container & Title) -->
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <!-- Segmented Tab Switcher -->
                <div class="d-flex justify-content-center justify-content-lg-start mb-3">
                    <ul class="nav nav-pills bento-tabs-pill" id="skillsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="hard-skills-tab" data-bs-toggle="pill" data-bs-target="#hard-skills-pane" type="button" role="tab" aria-controls="hard-skills-pane" aria-selected="true">
                                <i class="bi bi-lightning-charge-fill me-1"></i> Hard Skills ({{ $hardSkillCategory?->skills->count() ?? 0 }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="soft-skills-tab" data-bs-toggle="pill" data-bs-target="#soft-skills-pane" type="button" role="tab" aria-controls="soft-skills-pane" aria-selected="false">
                                <i class="bi bi-people-fill me-1"></i> Soft Skills ({{ $softSkillCategory?->skills->count() ?? 0 }})
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content: Dynamic Skill Pyramid Diagram -->
                <div class="tab-content flex-grow-1 d-flex flex-column justify-content-center" id="skillsTabContent">
                    <!-- Pane 1: Hard Skills (Piramida Diagram Dinamis) -->
                    <div class="tab-pane fade show active flex-grow-1 d-flex align-items-center" id="hard-skills-pane" role="tabpanel" aria-labelledby="hard-skills-tab">
                        @include('portfolio.partials.skill-pyramid', [
                            'tiers' => $hardSkillTiers,
                            'type' => 'hard',
                            'category' => $hardSkillCategory,
                        ])
                    </div>

                    <!-- Pane 2: Soft Skills (Piramida Diagram Dinamis) -->
                    <div class="tab-pane fade flex-grow-1 d-flex align-items-center" id="soft-skills-pane" role="tabpanel" aria-labelledby="soft-skills-tab">
                        @include('portfolio.partials.skill-pyramid', [
                            'tiers' => $softSkillTiers,
                            'type' => 'soft',
                            'category' => $softSkillCategory,
                        ])
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Analisis & Desain Sistem + Blueprint Lab Callout (Struktur Padat, Elegan, Seimbang & Mobile-First) -->
            <div class="col-lg-6 d-flex flex-column">
                <div class="inspo-card p-3 p-sm-4 bg-white h-100 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Header Analisis & Desain Sistem -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6 flex-shrink-0">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </span>
                                <h4 class="mb-0 font-heading fs-6 fs-sm-5 fw-bold text-dark text-break">
                                    {{ $analysisSkillCategory->name ?? 'Analisis & Desain Sistem' }}
                                </h4>
                            </div>
                            <span class="badge bg-light text-muted border border-secondary border-opacity-10 rounded-pill px-2.5 px-sm-3 py-1 small fw-semibold flex-shrink-0">
                                Arsitektur &amp; Data
                            </span>
                        </div>

                        <!-- Analysis Skills Structured Competencies -->
                        @if(!$analysisSkillCategory || $analysisSkillCategory->skills->isEmpty())
                            <p class="text-muted">Skill belum ditambahkan.</p>
                        @else
                            <div class="d-flex flex-column gap-2.5 gap-sm-3 mb-3">
                                @foreach($analysisSkillCategory->skills as $skill)
                                    <div class="analysis-skill-card d-flex align-items-center justify-content-between gap-2 gap-sm-3">
                                        <div class="d-flex align-items-center gap-2.5 gap-sm-3 min-w-0 flex-grow-1">
                                            <div class="analysis-icon-box flex-shrink-0">
                                                @if(str_contains(strtolower($skill->name), 'erd') || str_contains(strtolower($skill->name), 'database'))
                                                    <i class="bi bi-database-check"></i>
                                                @elseif(str_contains(strtolower($skill->name), 'uml') || str_contains(strtolower($skill->name), 'flowchart'))
                                                    <i class="bi bi-diagram-2"></i>
                                                @else
                                                    <i class="bi bi-layers-half"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-grow-1">
                                                <div class="fw-bold text-dark text-break" style="font-size: clamp(0.85rem, 2vw, 0.95rem); line-height: 1.35;">
                                                    {{ $skill->name }}
                                                </div>
                                                <div class="text-muted small text-break" style="font-size: clamp(0.74rem, 1.8vw, 0.8rem); margin-top: 2px; line-height: 1.35;">
                                                    @if(str_contains(strtolower($skill->name), 'erd') || str_contains(strtolower($skill->name), 'database'))
                                                        Normalisasi 3NF, Skema Relasi &amp; Efisiensi Query
                                                    @elseif(str_contains(strtolower($skill->name), 'uml') || str_contains(strtolower($skill->name), 'flowchart'))
                                                        Use Case, Sequence Diagram &amp; Flowchart Sistem
                                                    @elseif(str_contains(strtolower($skill->name), 'pattern') || str_contains(strtolower($skill->name), 'solid'))
                                                        Clean Architecture, Prinsip SOLID &amp; Pola OOP
                                                    @else
                                                        Spesifikasi Kebutuhan &amp; Rekayasa Alur Terstruktur
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill fw-bold px-2 px-sm-3 py-1 py-sm-1.5 small flex-shrink-0" style="font-size: clamp(0.72rem, 1.8vw, 0.82rem);">
                                            {{ $skill->level_label ?: 'Terverifikasi' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Modern Documentation & Blueprint Vault Callout (Tersambung Rapi di Bawah) -->
                    <div class="blueprint-vault-card p-3 p-sm-4 rounded-4 mt-2 position-relative overflow-hidden">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-1">
                            <span class="blueprint-badge-pill flex-shrink-0">
                                <i class="bi bi-stars text-warning me-1"></i> REAL ARTIFACTS
                            </span>
                            <span class="blueprint-badge-white flex-shrink-0">
                                <i class="bi bi-folder2-open text-primary me-1"></i> BLUEPRINT LAB
                            </span>
                        </div>
                        <h5 class="font-heading fs-6 fs-sm-5 mb-1.5 text-white text-break">
                            Intip Blueprint, ERD &amp; Alur Sistem Riil
                        </h5>
                        <p class="text-white text-opacity-90 small mb-3 lh-sm" style="font-size: clamp(0.8rem, 2vw, 0.85rem);">
                            Bukan cuma teori! Buka brankas dokumentasi arsitektur: diagram ERD 3NF, flowchart UML, dan berkas teknis yang siap ditinjau &amp; diunduh.
                        </p>
                        <a href="{{ route('portfolio.documentations.index') }}" class="btn-blueprint-cta">
                            <span>Buka Arsip Dokumentasi &amp; Blueprint</span>
                            <i class="bi bi-arrow-right-short fs-4 flex-shrink-0"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. PENGALAMAN PROYEK (ALA GAMBAR 2: MAX 2 CONTAINER DENGAN SLIDER) -->
    <section id="proyek" class="py-5 inspo-section">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2">
            <div>
                <h2 class="inspo-header-title mb-1">Pengalaman Proyek</h2>
                <p class="inspo-header-subtitle">Solusi web full-stack siap produksi dengan arsitektur data ternormalisasi & teruji.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-muted rounded-pill px-3 py-2 small fw-bold me-1">
                    {{ $projects->count() }} Proyek
                </span>
                <button type="button" class="btn-slider-nav btn-slider-prev" data-target="#projectSliderTrack" aria-label="Geser ke kiri" title="Geser ke kiri">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="btn-slider-nav btn-slider-next" data-target="#projectSliderTrack" aria-label="Geser ke kanan" title="Geser ke kanan">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        @if($projects->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Belum ada proyek yang diterbitkan.</p>
                </div>
            </div>
        @else
            <div class="slider-container-2col">
                <div class="slider-track-2col" id="projectSliderTrack">
                    @foreach($projects as $index => $project)
                        <div class="project-slide-item slider-item-2col" data-index="{{ $index }}">
                            @include('portfolio.partials.project-card', ['project' => $project])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- 7. PENGALAMAN MAGANG & ORGANISASI (DIGABUNG MENJADI 1 MASTER SECTION DENGAN FILTER & BENTO STYLE) -->
    <section id="pengalaman" class="py-5 inspo-section">
        <!-- Anchor points for backward compatibility -->
        <span id="magang" style="display: block; position: relative; top: -100px; visibility: hidden;"></span>
        <span id="organisasi" style="display: block; position: relative; top: -100px; visibility: hidden;"></span>

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4 pb-2">
            <div>
                <span class="inspo-section-pill">REKAM JEJAK &amp; KEPEMIMPINAN</span>
                <h2 class="inspo-header-title mb-1">Pengalaman Magang &amp; Organisasi</h2>
                <p class="inspo-header-subtitle">Kombinasi kontribusi industri riil korporat dan rekam jejak kepengurusan organisasi.</p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Filter Pills -->
                <div class="bento-filter-group d-flex align-items-center gap-1 p-1 rounded-pill">
                    <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold bento-filter-btn active" data-filter="all">
                        Semua ({{ $internships->count() + $organizations->count() }})
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold bento-filter-btn" data-filter="magang">
                        <span class="badge bg-warning rounded-circle p-1 me-1" style="width: 8px; height: 8px; display: inline-block;"></span> Magang ({{ $internships->count() }})
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold bento-filter-btn" data-filter="organisasi">
                        <span class="badge rounded-circle p-1 me-1" style="width: 8px; height: 8px; display: inline-block; background-color: #8b5cf6;"></span> Organisasi ({{ $organizations->count() }})
                    </button>
                </div>

                <!-- Slider Nav Buttons -->
                <div class="d-flex align-items-center gap-1 ms-sm-2">
                    <button type="button" class="btn-slider-nav btn-slider-prev" data-target="#experienceSliderTrack" aria-label="Geser ke kiri" title="Geser ke kiri">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="btn-slider-nav btn-slider-next" data-target="#experienceSliderTrack" aria-label="Geser ke kanan" title="Geser ke kanan">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        @php
            $allExperiences = $internships->concat($organizations)->sortByDesc(fn($e) => $e->start_date ?? $e->created_at);
        @endphp

        @if($allExperiences->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Belum ada pengalaman yang diterbitkan.</p>
                </div>
            </div>
        @else
            <div class="slider-container-2col">
                <div class="slider-track-2col" id="experienceSliderTrack">
                    @foreach($allExperiences as $exp)
                        @php
                            $isIntern = $exp->type?->value === 'internship';
                        @endphp
                        <div class="experience-slide-item slider-item-2col" data-category="{{ $isIntern ? 'magang' : 'organisasi' }}">
                            @if($isIntern)
                                @include('portfolio.partials.internship-card', ['experience' => $exp])
                            @else
                                @include('portfolio.partials.organization-card', ['experience' => $exp])
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- 8. DOKUMENTASI PENGHARGAAN & SERTIFIKASI (KREDENSIAL, HKI, PRESTASI & SERTIFIKASI RESMI) -->
    <section id="sertifikasi" class="py-5 inspo-section position-relative" style="scroll-margin-top: 100px;">
        <!-- Anchors for smooth backward-compatible navigation without dirty inline styles -->
        <span id="penghargaan" class="position-absolute" style="top: -100px; left: 0;"></span>
        <span id="penghargaan-sertifikasi" class="position-absolute" style="top: -100px; left: 0;"></span>

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4 pb-2">
            <div>
                <span class="inspo-section-pill">KREDENSIAL &amp; PRESTASI</span>
                <h2 class="inspo-header-title mb-1">Dokumentasi Penghargaan &amp; Sertifikasi</h2>
                <p class="inspo-header-subtitle mb-0">Bukti kredensial resmi, hak cipta karya cipta (HKI), lisensi kompetensi, dan apresiasi capaian profesional.</p>
            </div>
            <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-2 w-100 w-lg-auto pt-1 pt-lg-0">
                @if(!$certifications->isEmpty())
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 small fw-bold d-sm-none">
                        {{ $certifications->count() }} Kredensial
                    </span>
                @endif
                <!-- <a href="{{ route('portfolio.documentations.index') }}" class="btn-inspo-header py-2 px-3 fs-6 d-none d-sm-inline-flex me-1">
                    <i class="bi bi-folder2-open me-1"></i> Buka Blueprint Lab
                </a> -->
                <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0">
                    <button type="button" class="btn-slider-nav btn-slider-prev" data-target="#certificationSliderTrack" aria-label="Geser ke kiri" title="Geser ke kiri">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="btn-slider-nav btn-slider-next" data-target="#certificationSliderTrack" aria-label="Geser ke kanan" title="Geser ke kanan">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($certifications->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12 text-center py-5">
                    <div class="p-4 p-md-5 bg-white rounded-4 border text-muted">
                        <i class="bi bi-award fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        Belum ada data penghargaan &amp; sertifikasi yang dipublikasikan.
                    </div>
                </div>
            </div>
        @else
            <div class="slider-container-3col">
                <div class="slider-track-3col" id="certificationSliderTrack">
                    @foreach($certifications as $certification)
                        <div class="certification-slide-item slider-item-3col">
                            @include('portfolio.partials.certification-card', ['certification' => $certification])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Modals for Certifications with Images or Files --}}
        @foreach($certifications as $certification)
            @include('portfolio.partials.certification-modal', ['certification' => $certification])
        @endforeach
    </section>

    <!-- 9. KONTAK (FORM TO MESSAGES - FULL WIDTH ALIGNED SEPERTI CONTAINER DI ATASNYA) -->
    <section id="kontak" class="py-5 inspo-section">
        <div class="row">
            <div class="col-12">
                <div class="contact-bento-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="inspo-section-pill">HUBUNGI SAYA</span>
                        <h2 class="inspo-header-title">Kirimkan Pesan</h2>
                        <p class="inspo-header-subtitle">Punya tawaran proyek, peluang kolaborasi, atau sekadar ingin berdiskusi? Tinggalkan pesan Anda.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert modern-alert-danger d-flex align-items-start gap-3 p-3 mb-4 rounded-4" role="alert">
                            <span class="fs-4 text-danger flex-shrink-0"><i class="bi bi-exclamation-triangle-fill"></i></span>
                            <div>
                                <strong class="font-heading text-danger d-block mb-1">Periksa kembali data Anda:</strong>
                                <ul class="mb-0 text-danger ps-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
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
                                <label for="name" class="form-label fw-bold small text-uppercase text-dark">Nama Anda *</label>
                                <input type="text" name="name" id="name" class="form-control form-control-modern py-2 px-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Budi Santoso" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold small text-uppercase text-dark">Alamat Email *</label>
                                <input type="email" name="email" id="email" class="form-control form-control-modern py-2 px-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="budi@perusahaan.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="form-label fw-bold small text-uppercase text-dark">Subjek (Opsional)</label>
                            <input type="text" name="subject" id="subject" class="form-control form-control-modern py-2 px-3" value="{{ old('subject') }}" placeholder="Diskusi Proyek Web Enterprise">
                        </div>

                        <div>
                            <label for="body" class="form-label fw-bold small text-uppercase text-dark">Isi Pesan *</label>
                            <textarea name="body" id="body" rows="5" class="form-control form-control-modern rounded-4 p-3 @error('body') is-invalid @enderror" placeholder="Tuliskan kebutuhan Anda secara detail..." required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="attachments" class="form-label fw-bold small text-uppercase text-dark d-flex flex-wrap align-items-center justify-content-between gap-1">
                                <span><i class="bi bi-paperclip me-1 text-primary"></i> Lampiran Berkas / Gambar (Bisa Banyak File, Opsional)</span>
                                <span class="text-muted text-lowercase fw-normal" style="font-size: 0.78rem;">Maks. 5 Berkas @ 10 MB</span>
                            </label>
                            <input type="file" name="attachments[]" id="attachments" multiple class="form-control form-control-modern py-2 px-3 @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.csv,image/*">
                            <div id="attachments-file-list" class="mt-2 d-none"></div>
                            <div class="form-text small text-muted mt-1">
                                <i class="bi bi-info-circle text-primary me-1"></i> Mendukung dokumen (PDF, Word, Excel, ZIP) atau format gambar (JPG, PNG, WebP, SVG). Berkas otomatis terlampir di email dan dapat diunduh.
                            </div>
                            @error('attachments')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('attachment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" class="btn-bento-primary py-3 px-5 fs-5 shadow">
                                <span>Kirim Pesan Sekarang</span> <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('attachments');
        const preview = document.getElementById('attachments-file-list');

        if (input && preview) {
            input.addEventListener('change', function () {
                preview.innerHTML = '';
                if (this.files && this.files.length > 0) {
                    preview.classList.remove('d-none');
                    const container = document.createElement('div');
                    container.className = 'd-flex flex-column gap-2 p-2 rounded-3 bg-light border';

                    let totalBytes = 0;
                    Array.from(this.files).forEach(function (file) {
                        totalBytes += file.size;
                        const sizeStr = file.size >= 1048576 
                            ? (file.size / 1048576).toFixed(1) + ' MB' 
                            : (file.size / 1024).toFixed(1) + ' KB';

                        const item = document.createElement('div');
                        item.className = 'd-flex align-items-center justify-content-between p-2 rounded bg-white border border-1 text-dark small';
                        
                        const left = document.createElement('div');
                        left.className = 'd-flex align-items-center text-truncate me-2';
                        left.innerHTML = '<i class="bi bi-file-earmark-check text-primary me-2 fs-6"></i>';
                        
                        const nameSpan = document.createElement('span');
                        nameSpan.className = 'fw-semibold text-truncate';
                        nameSpan.textContent = file.name;
                        left.appendChild(nameSpan);

                        const badge = document.createElement('span');
                        badge.className = 'badge bg-secondary ms-2 shrink-0';
                        badge.textContent = sizeStr;

                        item.appendChild(left);
                        item.appendChild(badge);
                        container.appendChild(item);
                    });

                    const totalStr = totalBytes >= 1048576 
                        ? (totalBytes / 1048576).toFixed(1) + ' MB' 
                        : (totalBytes / 1024).toFixed(1) + ' KB';

                    const footer = document.createElement('div');
                    footer.className = 'small text-muted pt-1 ps-1';
                    footer.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Terpilih <strong>' + input.files.length + ' berkas</strong> (Total: ' + totalStr + ')';
                    container.appendChild(footer);

                    preview.appendChild(container);
                } else {
                    preview.classList.add('d-none');
                }
            });
        }
    });
</script>
@endsection
