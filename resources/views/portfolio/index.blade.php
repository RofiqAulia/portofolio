@extends('layouts.app')

@section('title', 'Portofolio M Rofiq Aulia')
@section('body_class', 'page-home')
@section('meta_description', 'Portofolio M. Rofiq Aulia, Sarjana Terapan Teknik Informatika. Berfokus pada IT Business Analyst, System Analyst, dan Software Engineer berbasis PHP Laravel.')
@section('meta_keywords', 'Portofolio Software Engineer, System Analyst Indonesia, Business Analyst Fresh Graduate, Web Developer PHP Laravel, Teknik Informatika, continuous improvement')

@section('content')
<div class="container">

    <!-- 1. HERO BENTO MASTER STAGE -->
    <section id="tentang" aria-labelledby="hero-main-title">
        @php
            $techList = $technologies->isNotEmpty() ? $technologies : collect([
                (object)['name' => 'Laravel', 'slug' => 'laravel', 'icon_path' => 'logo/laravel-logo.svg'],
                (object)['name' => 'PHP', 'slug' => 'php', 'icon_path' => 'logo/php-logo.svg'],
                (object)['name' => 'MySQL', 'slug' => 'mysql', 'icon_path' => 'logo/mysql-logo.svg'],
                (object)['name' => 'Bootstrap 5', 'slug' => 'bootstrap-5', 'icon_path' => 'logo/bootstrap-5-logo.svg'],
                (object)['name' => 'Vue.js', 'slug' => 'vue-js', 'icon_path' => 'logo/vue-logo.svg'],
                (object)['name' => 'React', 'slug' => 'react', 'icon_path' => 'logo/react-logo.svg'],
                (object)['name' => 'Tailwind CSS', 'slug' => 'tailwind-css', 'icon_path' => 'logo/tailwind-css-logo.svg'],
                (object)['name' => 'Redis', 'slug' => 'redis', 'icon_path' => 'logo/redis-logo.svg'],
                (object)['name' => 'Docker', 'slug' => 'docker', 'icon_path' => 'logo/docker-logo.svg'],
                (object)['name' => 'Git', 'slug' => 'git', 'icon_path' => 'logo/git-logo.svg'],
                (object)['name' => 'Notion', 'slug' => 'notion', 'icon_path' => 'logo/notion-logo.svg'],
                (object)['name' => 'Power Automate', 'slug' => 'power-automate', 'icon_path' => 'logo/power-automate-logo.svg'],
            ]);
            $heroTagline = trim((string) ($profile->tagline ?? ''));
            if ($heroTagline === '') {
                $heroTagline = 'Fresh Graduate Sarjana Terapan Teknik Informatika Politeknik Negeri Malang dengan pengalaman magang industri dan proyek enterprise berbasis Laravel.';
            }
        @endphp

        <div class="hero-meta">
            <div class="hero-meta-row">
                <div class="hero-meta-box">
                <div class="hero-meta-left">
                    <span class="hero-meta-status">
                        <span class="hero-meta-dot"></span>
                        {{ $profile && $profile->is_open_to_work ? 'Open to work' : 'Portofolio' }}
                    </span>
                    <span class="hero-meta-item">Portfolio — {{ date('Y') }}</span>
                </div>
                <span class="hero-meta-mid">System analyst &amp; software engineer</span>

                <span class="hero-meta-item">{{ $profile->location ?? 'Malang, ID' }}</span>
                </div>

                <div class="hero-tools-line" aria-hidden="true">
                    <div class="hero-meta-marquee">
                <div class="hero-meta-marquee-track">
                    @for($toolPass = 0; $toolPass < 2; $toolPass++)
                        @foreach($techList as $tech)
                            <span class="hero-meta-tool">{{ $tech->name }}</span>
                            <span class="hero-meta-tool-dot"></span>
                        @endforeach
                    @endfor
                </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-split">
            <div class="hero-portrait-panel">
                @if(!empty($profile->photo_path))
                    <img src="{{ asset('storage/' . $profile->photo_path) }}"
                         itemprop="image"
                         alt="Foto profil {{ $profile->full_name ?? 'M. Rofiq Aulia' }} - System Analyst &amp; Software Engineer Indonesia"
                         class="hero-portrait"
                         width="500"
                         height="500"
                         fetchpriority="high"
                         decoding="async"
                         role="button"
                         tabindex="0"
                         aria-label="Lihat foto profil {{ $profile->full_name }}"
                         data-lightbox-trigger
                         data-image-src="{{ asset('storage/' . $profile->photo_path) }}"
                         data-image-title="{{ $profile->full_name }}"
                         data-image-desc="{{ $profile->headline ?? 'Foto Profil' }}"
                         data-image-download="{{ asset('storage/' . $profile->photo_path) }}"
                         data-image-filename="{{ Str::slug($profile->full_name) }}-profile">
                @else
                    <div class="hero-portrait-fallback">
                        <span>{{ $profile->full_name ?? 'M. Rofiq Aulia' }}</span>
                    </div>
                @endif
            </div>

            <div class="hero-copy-panel">
                <p class="hero-kicker">
                    {{ $profile && $profile->is_open_to_work ? 'Open to work' : 'Portofolio' }} — {{ $profile->location ?? 'Malang, ID' }}
                </p>

                <h1 id="hero-main-title" class="hero-title">
                    Siap berkontribusi nyata dalam proyek &amp; <span class="hero-title-accent">perusahaan&nbsp;Anda</span>
                </h1>

                <p class="hero-lead">{{ $heroTagline }}</p>

                <div class="hero-actions">
                    <a href="https://wa.me/6282228035426" target="_blank" rel="noopener noreferrer" class="hero-btn-light js-wa-btn" aria-label="Mulai diskusi proyek via WhatsApp">
                        <span>Diskusi proyek</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                    <a href="#proyek" class="hero-btn-ghost" aria-label="Lihat portofolio proyek">
                        <span>Lihat portofolio</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                    @if(!empty($profile->cv_path))
                        <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="hero-btn-ghost" aria-label="Unduh berkas curriculum vitae">
                            <span>Unduh CV</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>

    </section>

    <!-- 3. BENTO GRID SHOWCASE (STATISTIK, REKAM JEJAK & FILOSOFI ALA GAMBAR 1) -->
    <section class="py-5 bento-showcase-section" id="overview">
        <!-- Header: Title on Left, Action Button on Right (Ala Gambar 1) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2">
            <div>
                <p class="section-eyebrow">Ringkasan</p>
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
                            <span class="bento-stat-number">{{ $projects->count() }}</span>
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
                            <span class="bento-stat-number">{{ $internships->count() }}</span>
                            <span class="bento-stat-label">Pengalaman Magang</span>
                        </div>
                        <p class="bento-desc">
                            Kontribusi aktif dan implementasi sistem skala enterprise pada lingkungan korporasi industri manufaktur berskala nasional.
                        </p>
                        <a href="#magang" class="bento-link" aria-label="Telusuri jejak magang industri">
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
                                <p class="mb-0 fw-bold fs-6">{{ $profile->quote_author ?? 'M. Rofiq Aulia' }}</p>
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
                        <h3 class="bento-card-heading fs-4">Blueprint & Diagram Lab</h3>
                        <p class="bento-desc">
                            Bukan cuma teori! Buka brankas dokumentasi arsitektur: diagram ERD 3NF, flowchart proses bisnis & UML.
                        </p>
                        <a href="{{ route('portfolio.documentations.index') }}" class="btn-bento-pill" aria-label="Buka arsip blueprint dan diagram lab">
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
                            <span class="bento-stat-number">{{ $organizations->count() }}</span>
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
                        <h3 class="bento-card-heading fs-4">Penghargaan &amp; Sertifikasi</h3>
                        <p class="bento-desc">
                            Dokumentasi resmi sertifikasi kompetensi magang industri, lisensi kepemimpinan, dan hak cipta karya (HKI).
                        </p>
                        <a href="{{ route('portfolio.certifications.index') }}" class="bento-link" aria-label="Buka arsip penghargaan dan sertifikasi">
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
        @php
            $hardSkills = $hardSkillCategory?->skills ?? collect();
            $softSkills = $softSkillCategory?->skills ?? collect();
            $analysisSkills = $analysisSkillCategory?->skills ?? collect();
            $totalSkills = $hardSkills->count() + $softSkills->count() + $analysisSkills->count();
        @endphp

        <div class="skills-head">
            <div class="skills-head-copy">
                <span class="inspo-section-pill">KEAHLIAN &amp; KOMPETENSI</span>
                <h2 class="inspo-header-title">Keahlian Teruji</h2>
                <p class="inspo-header-subtitle">
                    Kombinasi kemampuan rekayasa backend, pengolahan database performa tinggi, serta kepemimpinan dan analisis desain sistem.
                </p>
            </div>

            <div class="skills-filter" role="group" aria-label="Filter keahlian">
                <button type="button" class="skills-filter-btn is-active" data-skill-filter="hard" aria-pressed="true">
                    Hard skills <span>{{ $hardSkills->count() }}</span>
                </button>
                <button type="button" class="skills-filter-btn" data-skill-filter="soft" aria-pressed="false">
                    Soft skills <span>{{ $softSkills->count() }}</span>
                </button>
                <button type="button" class="skills-filter-btn" data-skill-filter="analysis" aria-pressed="false">
                    Analisis <span>{{ $analysisSkills->count() }}</span>
                </button>
            </div>
        </div>

        <div class="skills-stack" id="skillsStack" data-active="hard">
            {{-- Hard skills --}}
            <div class="skills-rail" data-group="hard" data-skill-rail>
                <div class="skills-rail-head">
                    <div class="skills-rail-title">
                        <i class="bi bi-code-slash skills-rail-icon is-hard"></i>
                        <span class="skill-col-rule is-hard"></span>
                        <h3>Hard skills</h3>
                        <span class="skills-rail-count">{{ $hardSkills->count() }}</span>
                    </div>
                    <div class="skills-rail-nav">
                        <button type="button" class="skills-rail-btn skills-rail-prev" aria-label="Geser hard skills ke kiri">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="skills-rail-btn skills-rail-next" aria-label="Geser hard skills ke kanan">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <p class="skills-rail-desc">Bahasa pemrograman, basis data, dan perkakas yang saya pakai untuk membangun sistem.</p>

                <div class="skills-rail-track" role="list">
                    @forelse($hardSkills as $skill)
                        <span class="skill-chip" role="listitem">{{ $skill->name }}</span>
                    @empty
                        <span class="skill-chip is-empty">Skill belum ditambahkan.</span>
                    @endforelse
                </div>
            </div>

            {{-- Soft skills --}}
            <div class="skills-rail" data-group="soft" data-skill-rail>
                <div class="skills-rail-head">
                    <div class="skills-rail-title">
                        <i class="bi bi-people skills-rail-icon is-soft"></i>
                        <span class="skill-col-rule is-soft"></span>
                        <h3>Soft skills</h3>
                        <span class="skills-rail-count">{{ $softSkills->count() }}</span>
                    </div>
                    <div class="skills-rail-nav">
                        <button type="button" class="skills-rail-btn skills-rail-prev" aria-label="Geser soft skills ke kiri">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="skills-rail-btn skills-rail-next" aria-label="Geser soft skills ke kanan">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <p class="skills-rail-desc">Cara saya bekerja dengan tim, pengguna, dan tenggat yang ketat.</p>

                <div class="skills-rail-track" role="list">
                    @forelse($softSkills as $skill)
                        <span class="skill-chip" role="listitem">{{ $skill->name }}</span>
                    @empty
                        <span class="skill-chip is-empty">Skill belum ditambahkan.</span>
                    @endforelse
                </div>
            </div>

            {{-- Analisis & desain --}}
            <div class="skills-rail" data-group="analysis" data-skill-rail>
                <div class="skills-rail-head">
                    <div class="skills-rail-title">
                        <i class="bi bi-diagram-3 skills-rail-icon is-analysis"></i>
                        <span class="skill-col-rule is-analysis"></span>
                        <h3>Analisis &amp; desain</h3>
                        <span class="skills-rail-count">{{ $analysisSkills->count() }}</span>
                    </div>
                    <div class="skills-rail-nav">
                        <button type="button" class="skills-rail-btn skills-rail-prev" aria-label="Geser analisis ke kiri">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="skills-rail-btn skills-rail-next" aria-label="Geser analisis ke kanan">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <p class="skills-rail-desc">Alur menerjemahkan kebutuhan bisnis menjadi rancangan sistem yang bisa diuji.</p>

                <div class="skills-rail-track" role="list">
                    @forelse($analysisSkills as $skill)
                        @php
                            $name = strtolower($skill->name);
                            if (str_contains($name, 'erd') || str_contains($name, 'database')) {
                                $note = 'Normalisasi 3NF, Skema Relasi & Efisiensi Query';
                            } elseif (str_contains($name, 'uml') || str_contains($name, 'flowchart')) {
                                $note = 'Use Case, Sequence Diagram & Flowchart Sistem';
                            } elseif (str_contains($name, 'pattern') || str_contains($name, 'solid')) {
                                $note = 'Clean Architecture, Prinsip SOLID & Pola OOP';
                            } else {
                                $note = 'Spesifikasi Kebutuhan & Rekayasa Alur Terstruktur';
                            }
                        @endphp
                        <span class="skill-chip" role="listitem" title="{{ $note }}">{{ $skill->name }}</span>
                    @empty
                        <span class="skill-chip is-empty">Skill belum ditambahkan.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var stack = document.getElementById('skillsStack');
                var buttons = document.querySelectorAll('[data-skill-filter]');

                buttons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        buttons.forEach(function (other) {
                            other.classList.remove('is-active');
                            other.setAttribute('aria-pressed', 'false');
                        });
                        var value = button.getAttribute('data-skill-filter');
                        var isSame = stack.getAttribute('data-active') === value;

                        if (isSame) {
                            stack.setAttribute('data-active', 'all');
                        } else {
                            button.classList.add('is-active');
                            button.setAttribute('aria-pressed', 'true');
                            stack.setAttribute('data-active', value);
                        }
                        requestAnimationFrame(function () {
                            document.querySelectorAll('[data-skill-rail]').forEach(function (rail) {
                                rail.dispatchEvent(new CustomEvent('rail:refresh'));
                            });
                        });
                    });
                });

                document.querySelectorAll('[data-skill-rail]').forEach(function (rail) {
                    var track = rail.querySelector('.skills-rail-track');
                    var prev = rail.querySelector('.skills-rail-prev');
                    var next = rail.querySelector('.skills-rail-next');

                    function update() {
                        var max = track.scrollWidth - track.clientWidth;
                        var scrollable = max > 2;
                        rail.classList.toggle('is-scrollable', scrollable);
                        prev.disabled = track.scrollLeft <= 2;
                        next.disabled = track.scrollLeft >= max - 2;
                    }

                    function step(direction) {
                        var amount = Math.max(240, track.clientWidth * 0.75);
                        track.scrollBy({ left: direction * amount, behavior: 'smooth' });
                    }

                    prev.addEventListener('click', function () { step(-1); });
                    next.addEventListener('click', function () { step(1); });
                    track.addEventListener('scroll', update, { passive: true });
                    rail.addEventListener('rail:refresh', function () { requestAnimationFrame(update); });
                    window.addEventListener('resize', update);
                    update();
                });
            });
        </script>
    </section>

    <!-- 6. PENGALAMAN PROYEK (DESKTOP: 4 CONTAINER SLIDER, MOBILE: 1 CONTAINER SLIDER) -->
    <section id="proyek" class="py-5 inspo-section">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2">
            <div>
                <p class="section-eyebrow">Proyek terpilih</p>
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
            <div class="slider-container-4col">
                <div class="slider-track-4col" id="projectSliderTrack">
                    @foreach($projects as $index => $project)
                        <div class="project-slide-item slider-item-4col" data-index="{{ $index }}">
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

                    <form id="contact-form" action="{{ route('portfolio.contact.send') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
                        @csrf

                        <!-- Token Google reCAPTCHA v3 -->
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                        @error('recaptcha_token')
                            <div class="alert modern-alert-danger p-2 small mb-2 text-danger rounded-3">{{ $message }}</div>
                        @enderror

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

        // reCAPTCHA v3 Interceptor untuk Formulir Kontak
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                if (window.recaptchaSiteKey) {
                    const tokenInput = document.getElementById('recaptcha_token');
                    if (tokenInput && !tokenInput.value) {
                        e.preventDefault();
                        const submitBtn = contactForm.querySelector('button[type="submit"]');
                        const originalHtml = submitBtn ? submitBtn.innerHTML : '';
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span>Verifikasi Keamanan...</span> <i class="bi bi-shield-check ms-2"></i>';
                        }

                        if (typeof window.ensureRecaptcha === 'function') {
                            window.ensureRecaptcha(function () {
                                if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                                    grecaptcha.ready(function () {
                                        grecaptcha.execute(window.recaptchaSiteKey, { action: 'contact_form' }).then(function (token) {
                                            tokenInput.value = token;
                                            contactForm.submit();
                                        }).catch(function (error) {
                                            console.warn('reCAPTCHA error:', error);
                                            if (submitBtn) {
                                                submitBtn.disabled = false;
                                                submitBtn.innerHTML = originalHtml;
                                            }
                                            contactForm.submit();
                                        });
                                    });
                                } else {
                                    contactForm.submit();
                                }
                            });
                        } else {
                            contactForm.submit();
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
