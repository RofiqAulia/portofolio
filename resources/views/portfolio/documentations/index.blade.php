@extends('layouts.app')

@section('title', 'Dokumentasi, ERD & Blueprint Sistem — ' . ($profile->full_name ?? 'M. Rofiq Aulia'))
@section('meta_description', 'Arsip dokumentasi rekayasa perangkat lunak, perancangan ERD database teroptimasi, diagram UML flowchart, dan blueprint arsitektur sistem.')

@section('content')
<div class="container py-4">

    <!-- 1. Header / Breadcrumbs -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <a href="{{ route('portfolio.home') }}" class="btn-bento-secondary py-2 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
        <div class="d-flex align-items-center gap-2">
            <span class="bento-badge-pill bento-badge-amber py-1 px-3">
                <i class="bi bi-stars"></i> BLUEPRINT LAB
            </span>
            <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-bold">
                {{ $documentations->count() }} Artefak Terdaftar
            </span>
        </div>
    </div>

    <!-- 2. Hero Section: Royal Blue Blueprint & Diagram Lab (Ala Bento Card 5) -->
    <section class="py-2 mb-4">
        <div class="bento-card bento-card-royal p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 32px; min-height: auto;">
            <div class="row align-items-center gy-4 position-relative z-2">
                <div class="col-lg-8">
                    <span class="bento-badge mb-2">ARSIP ARTIFAK &amp; BLUEPRINT LAB</span>
                    <h1 class="display-5 font-heading mb-3 text-white">
                        Dokumentasi, ERD &amp; Blueprint Sistem
                    </h1>
                    <p class="fs-5 text-white text-opacity-90 mb-4 pe-lg-4" style="line-height: 1.6;">
                        Bukan cuma teori! Koleksi brankas artefak teknis perancangan perangkat lunak: rancangan skema <strong>ERD database 3NF</strong>, diagram alur <strong>UML sequence &amp; flowchart</strong>, arsitektur <strong>clean code / SOLID</strong>, dan dokumen spesifikasi riil yang dapat Anda tinjau dalam resolusi tinggi atau unduh secara langsung.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="bento-tag">
                            <i class="bi bi-check-circle-fill text-warning me-1"></i> Teruji &amp; Siap Produksi
                        </span>
                        <span class="bento-tag">
                            <i class="bi bi-image text-white me-1"></i> Preview Resolusi Penuh
                        </span>
                        <span class="bento-tag">
                            <i class="bi bi-download text-warning me-1"></i> Bebas Diunduh
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="p-4 rounded-4 text-start w-100" style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.25); color: #FFFFFF;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-white text-primary rounded-circle p-2 fs-5 shadow-sm">
                                <i class="bi bi-cpu"></i>
                            </span>
                            <h5 class="mb-0 font-heading text-white">Standar Rekayasa</h5>
                        </div>
                        <ul class="list-unstyled mb-0 vstack gap-2 small text-white text-opacity-90">
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-warning fw-bold mt-1"></i>
                                <span>Third Normal Form (3NF) relational database</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-warning fw-bold mt-1"></i>
                                <span>UML 2.5 Sequence &amp; Activity Diagrams</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-warning fw-bold mt-1"></i>
                                <span>SOLID Principles &amp; Clean Architecture</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Visual Diagram Background Decoration -->
            <div class="bento-visual bento-visual-diagram" aria-hidden="true" style="opacity: 0.14;">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
        </div>
    </section>

    <!-- 3. Filter & Pencarian (Modern Bento Filter Bar) -->
    <section class="py-2 mb-4">
        <div class="bento-detail-card p-3 p-md-4 bg-white">
            <form action="{{ route('portfolio.documentations.index') }}" method="GET" class="row g-3 align-items-center">
                <!-- Search input -->
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border border-end-0 rounded-start-pill ps-3" style="border-color: rgba(10, 27, 61, 0.1) !important;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="q" 
                               class="form-control bg-light border border-start-0 rounded-end-pill py-2 pe-3" 
                               style="border-color: rgba(10, 27, 61, 0.1) !important;"
                               placeholder="Cari ERD, diagram UML, atau nama proyek..." 
                               value="{{ $search ?? '' }}">
                    </div>
                </div>

                <!-- Category filter dropdown -->
                <div class="col-sm-6 col-lg-3">
                    <select name="kategori" class="form-select border rounded-pill py-2 px-3" style="border-color: rgba(10, 27, 61, 0.1) !important;" onchange="this.form.submit()">
                        <option value="all">Semua Kategori ({{ $documentations->count() }})</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($selectedCategory ?? '') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Project filter dropdown -->
                <div class="col-sm-6 col-lg-3">
                    <select name="proyek" class="form-select border rounded-pill py-2 px-3" style="border-color: rgba(10, 27, 61, 0.1) !important;" onchange="this.form.submit()">
                        <option value="all">Semua Proyek Terkait</option>
                        @if(isset($projects))
                            @foreach($projects as $proj)
                                <option value="{{ $proj->slug }}" {{ ($selectedProject ?? '') === $proj->slug ? 'selected' : '' }}>
                                    📁 {{ Str::limit($proj->title, 26) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Type filter -->
                <div class="col-sm-6 col-lg-2 d-flex gap-2">
                    <select name="tipe" class="form-select border rounded-pill py-2 px-3" style="border-color: rgba(10, 27, 61, 0.1) !important;" onchange="this.form.submit()">
                        <option value="all">Semua Tipe</option>
                        <option value="image" {{ ($selectedType ?? '') === 'image' ? 'selected' : '' }}>🖼️ Diagram</option>
                        <option value="document" {{ ($selectedType ?? '') === 'document' ? 'selected' : '' }}>📄 Dokumen</option>
                        <option value="link" {{ ($selectedType ?? '') === 'link' ? 'selected' : '' }}>🌐 Tautan</option>
                    </select>
                    @if($selectedCategory || $selectedType || $selectedProject || $search)
                        <a href="{{ route('portfolio.documentations.index') }}" class="btn-bento-secondary p-2 flex-shrink-0" title="Reset Filter" style="width: 42px; height: 42px;">
                            <i class="bi bi-arrow-counterclockwise fs-5"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Quick category tags -->
            @if($categories->count() > 1)
                <div class="d-flex flex-wrap gap-2 pt-3 border-top mt-3 align-items-center">
                    <span class="small fw-bold text-muted me-1">Kategori Populer:</span>
                    <a href="{{ route('portfolio.documentations.index') }}" class="bento-badge-pill {{ empty($selectedCategory) || $selectedCategory === 'all' ? 'bento-badge-electric' : 'bento-badge-blue' }}" style="text-decoration: none;">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('portfolio.documentations.index', ['kategori' => $cat]) }}" class="bento-badge-pill {{ ($selectedCategory ?? '') === $cat ? 'bento-badge-electric' : 'bento-badge-blue' }}" style="text-decoration: none;">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Daftar Dokumen & Blueprint (Bento Card Grid) -->
    <section class="py-2">
        @if($documentations->isEmpty())
            <div class="bento-detail-card p-5 bg-white text-center my-4">
                <div class="display-3 text-muted mb-3 opacity-50">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h3 class="font-heading mb-2 text-dark">Belum Ada Dokumentasi yang Cocok</h3>
                <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                    Tidak ditemukan berkas atau diagram yang sesuai dengan kata kunci "{{ $search ?? '' }}". Silakan reset filter pencarian Anda.
                </p>
                <a href="{{ route('portfolio.documentations.index') }}" class="btn-bento-primary py-2 px-4">
                    <i class="bi bi-arrow-counterclockwise"></i> Tampilkan Semua Dokumentasi
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach($documentations as $doc)
                    <div class="col-md-6 col-lg-4 d-flex">
                        <div class="bento-detail-card h-100 w-100 d-flex flex-column bg-white overflow-hidden">
                            <!-- Visual Stage (Preview / Image / Icon placeholder) -->
                            @php
                                $previewUrl = $doc->getEffectivePreviewUrl();
                                $hasDownloadFile = $doc->hasFiles();
                                $fileCount = $doc->getFileCount();
                            @endphp

                            @if($previewUrl)
                                <div class="project-thumbnail-box position-relative overflow-hidden border-bottom" style="height: 220px; border-color: rgba(10, 27, 61, 0.08) !important;">
                                    <img src="{{ $previewUrl }}" 
                                         alt="{{ $doc->title }}" 
                                         class="w-100 h-100 object-fit-cover project-thumbnail-img"
                                         role="button"
                                         tabindex="0"
                                         aria-label="Lihat preview {{ $doc->title }}"
                                         data-lightbox-trigger
                                         data-image-src="{{ $previewUrl }}"
                                         data-image-title="{{ $doc->title }}"
                                         data-image-desc="{{ $doc->summary }}"
                                         data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $doc->slug) : $previewUrl }}"
                                         data-image-filename="{{ $doc->getDownloadFilename() }}">

                                    <!-- Hover Overlay (Desktop) -->
                                    <div class="project-thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                        <button type="button" 
                                                class="btn-bento-primary py-1 px-3 fs-6 btn-lightbox-open"
                                                data-image-src="{{ $previewUrl }}"
                                                data-image-title="{{ $doc->title }}"
                                                data-image-desc="{{ $doc->summary }}"
                                                data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $doc->slug) : $previewUrl }}"
                                                data-image-filename="{{ $doc->getDownloadFilename() }}"
                                                title="Lihat Gambar Penuh">
                                            <i class="bi bi-eye-fill"></i> View
                                        </button>
                                        @if($hasDownloadFile)
                                            <a href="{{ route('portfolio.documentations.download', $doc->slug) }}" 
                                               class="btn-bento-amber py-1 px-3 fs-6"
                                               title="{{ $fileCount > 1 ? 'Unduh Semua (' . $fileCount . ' Berkas .ZIP)' : 'Unduh Berkas Asli' }}">
                                                <i class="bi {{ $fileCount > 1 ? 'bi-file-zip' : 'bi-download' }}"></i> Unduh
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Badges on Image -->
                                    <div class="position-absolute top-0 start-0 m-2 d-flex flex-wrap gap-1">
                                        <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2 py-1 small">
                                            <i class="bi {{ $doc->type->icon() }}"></i> {{ $doc->getFileExtension() ? strtoupper($doc->getFileExtension()) : 'DIAGRAM' }}
                                        </span>
                                        @if($fileCount > 1)
                                            <span class="badge bg-primary text-white rounded-pill px-2 py-1 small fw-bold">
                                                <i class="bi bi-files"></i> {{ $fileCount }} Berkas
                                            </span>
                                        @endif
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 m-2 d-md-none">
                                        <button type="button" 
                                                class="btn-bento-primary p-0 rounded-circle btn-lightbox-open"
                                                style="width: 36px; height: 36px; min-height: 36px;"
                                                data-image-src="{{ $previewUrl }}"
                                                data-image-title="{{ $doc->title }}"
                                                data-image-desc="{{ $doc->summary }}"
                                                data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $doc->slug) : $previewUrl }}"
                                                data-image-filename="{{ $doc->getDownloadFilename() }}"
                                                aria-label="Lihat Gambar">
                                            <i class="bi bi-eye-fill small"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Non-Image Document Placeholder Card Header -->
                                <div class="w-100 bg-light p-4 d-flex flex-column align-items-center justify-content-center text-center border-bottom" style="height: 220px; border-color: rgba(10, 27, 61, 0.08) !important; background: radial-gradient(circle, rgba(92, 70, 229, 0.08) 10%, transparent 11%); background-size: 16px 16px;">
                                    <div class="badge bg-primary text-white rounded-circle p-3 mb-2 fs-3 shadow-sm">
                                        <i class="bi {{ $doc->type->icon() }}"></i>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-center gap-1 mt-1">
                                        <span class="badge bg-dark text-white rounded-pill px-3 py-1 small fw-bold">
                                            {{ $doc->getFileExtension() ? strtoupper($doc->getFileExtension()) : $doc->type->label() }}
                                        </span>
                                        @if($fileCount > 1)
                                            <span class="badge bg-primary text-white rounded-pill px-2 py-1 small fw-bold">
                                                <i class="bi bi-files"></i> {{ $fileCount }} Berkas
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Card Body -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                    <span class="bento-badge-pill bento-badge-blue py-0 px-2 small" style="font-size: 0.75rem;">
                                        {{ $doc->category }}
                                    </span>
                                    @if($doc->is_featured)
                                        <span class="bento-badge-pill bento-badge-amber py-0 px-2 small">
                                            ★ UNGGULAN
                                        </span>
                                    @endif
                                </div>

                                <h4 class="font-heading mb-2 fs-5">
                                    <a href="{{ route('portfolio.documentations.show', $doc->slug) }}" class="text-decoration-none text-dark">
                                        {{ $doc->title }}
                                    </a>
                                </h4>

                                @if($doc->summary)
                                    <p class="text-muted small mb-3 flex-grow-1">
                                        {{ Str::limit($doc->summary, 130) }}
                                    </p>
                                @endif

                                @if($doc->project)
                                    <div class="mb-3">
                                        <a href="{{ route('portfolio.project.show', $doc->project->slug) }}" 
                                           class="bento-badge-pill bento-badge-blue text-decoration-none py-1 px-3 d-inline-flex align-items-center gap-1"
                                           title="Buka detail proyek {{ $doc->project->title }}">
                                            <i class="bi bi-briefcase-fill text-primary"></i> 
                                            <span>Proyek: <strong>{{ Str::limit($doc->project->title, 28) }}</strong></span>
                                            <i class="bi bi-arrow-up-right small"></i>
                                        </a>
                                    </div>
                                @endif

                                <!-- Card Footer Actions -->
                                <div class="d-flex flex-wrap align-items-center gap-2 pt-3 border-top mt-auto" style="border-color: rgba(10, 27, 61, 0.08) !important;">
                                    <a href="{{ route('portfolio.documentations.show', $doc->slug) }}" class="btn-bento-primary py-2 px-3 fs-6">
                                        <span>Detail Dokumen</span> <i class="bi bi-arrow-right"></i>
                                    </a>

                                    @if($hasDownloadFile)
                                        <a href="{{ route('portfolio.documentations.download', $doc->slug) }}" class="btn-bento-amber py-2 px-3 fs-6" title="{{ $fileCount > 1 ? 'Unduh Semua (' . $fileCount . ' Berkas .ZIP)' : 'Unduh Berkas' }}">
                                            <i class="bi {{ $fileCount > 1 ? 'bi-file-zip' : 'bi-download' }}"></i> {{ $fileCount > 1 ? 'Unduh (' . $fileCount . ')' : 'Unduh' }}
                                        </a>
                                    @endif

                                    @if($doc->external_url)
                                        <a href="{{ $doc->external_url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-secondary p-2" title="Buka Tautan Eksternal" style="width: 42px; height: 42px;">
                                            <i class="bi bi-box-arrow-up-right fs-5"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection
