@extends('layouts.app')

@section('title', $project->title . ' — Portofolio')
@section('meta_description', Str::limit(strip_tags($project->summary), 150))

@section('content')
<div class="container py-4">
    <!-- Top Back Navigation -->
    <div class="mb-4">
        <a href="{{ route('portfolio.home') }}#proyek" class="btn-bento-secondary py-2 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Portofolio
        </a>
    </div>

    <div class="row gy-4">
        {{-- Main Project Detail (Bento Showcase) --}}
        <div class="col-lg-8">
            <div class="bento-detail-card p-4 p-md-5 bg-white mb-4">
                <!-- Badges -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @if($project->is_featured)
                        <span class="bento-badge-pill bento-badge-amber">
                            <i class="bi bi-star-fill text-warning"></i> PROYEK UNGGULAN
                        </span>
                    @endif
                    <span class="bento-badge-pill bento-badge-blue">
                        <i class="bi bi-code-slash text-primary"></i> {{ $project->role ?? 'Software Developer' }}
                    </span>
                    @if($project->status)
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ $project->status->label() }}
                        </span>
                    @endif
                </div>

                <h1 class="display-5 font-heading mb-3 text-dark">{{ $project->title }}</h1>

                <p class="fs-5 text-muted mb-4 lead">
                    {{ $project->summary }}
                </p>

                <!-- Thumbnail Showcase Box with Browser Mockup Header -->
                @if($project->thumbnail_path)
                    <div class="mb-4">
                        <div class="project-thumbnail-box position-relative overflow-hidden rounded-4 border" style="border-color: rgba(10, 27, 61, 0.08) !important; box-shadow: 0 10px 30px rgba(10, 27, 61, 0.06);">
                            <!-- Browser Bar Mockup -->
                            <div class="inspo-mockup-bar d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom">
                                <div class="inspo-mockup-dots d-flex align-items-center gap-1">
                                    <span class="dot dot-red"></span>
                                    <span class="dot dot-yellow"></span>
                                    <span class="dot dot-green"></span>
                                </div>
                                <span class="small text-muted font-monospace">{{ Str::slug($project->title) }}.app</span>
                                <span class="badge bg-white text-muted border rounded-pill px-2 py-0 small" style="font-size: 0.7rem;">Preview</span>
                            </div>

                            <img src="{{ asset('storage/' . $project->thumbnail_path) }}" 
                                 alt="{{ $project->title }}" 
                                 class="img-fluid w-100 object-fit-cover project-thumbnail-img" 
                                 style="max-height: 480px;"
                                 width="800"
                                 height="480"
                                 fetchpriority="high"
                                 role="button"
                                 tabindex="0"
                                 aria-label="Lihat gambar {{ $project->title }}"
                                 data-lightbox-trigger
                                 data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                 data-image-title="{{ $project->title }}"
                                 data-image-desc="{{ $project->summary }}"
                                 data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                                 data-image-filename="{{ Str::slug($project->title) }}-preview">

                            <!-- Hover Overlay (Desktop) -->
                            <div class="project-thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                <button type="button" 
                                        class="btn-bento-primary py-2 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                        data-image-title="{{ $project->title }}"
                                        data-image-desc="{{ $project->summary }}"
                                        data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                                        data-image-filename="{{ Str::slug($project->title) }}-preview">
                                    <i class="bi bi-eye-fill"></i> View Penuh
                                </button>
                                <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" class="btn-bento-amber py-2 px-3 fs-6">
                                    <i class="bi bi-download"></i> Unduh Gambar
                                </a>
                            </div>

                            <!-- Mobile Quick Actions -->
                            <div class="position-absolute bottom-0 end-0 m-3 d-md-none d-flex gap-2">
                                <button type="button" 
                                        class="btn-bento-primary py-1 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                        data-image-title="{{ $project->title }}"
                                        data-image-desc="{{ $project->summary }}"
                                        data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                                        data-image-filename="{{ Str::slug($project->title) }}-preview">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>
                                <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" class="btn-bento-amber py-1 px-3 fs-6">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Cerita & Arsitektur -->
                <div class="prose py-3 border-top border-bottom my-4">
                    <h4 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-journal-code"></i>
                        </span>
                        Cerita &amp; Arsitektur Proyek
                    </h4>
                    <div class="fs-6 leading-relaxed text-dark" style="color: #000000 !important;">
                        {!! $project->description ?: nl2br(e($project->summary)) !!}
                    </div>
                </div>

                <!-- Galeri Proyek -->
                @if($project->media->count())
                    <h4 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-images"></i>
                        </span>
                        Galeri Tangkapan Layar
                    </h4>
                    <div class="row g-3">
                        @foreach($project->media as $item)
                            <div class="col-md-6">
                                <div class="bento-detail-card p-2 bg-light">
                                    <div class="project-thumbnail-box position-relative overflow-hidden rounded-3 border" style="height: 200px;">
                                        <img src="{{ asset('storage/' . $item->path) }}" 
                                             alt="{{ $item->caption }}" 
                                             class="img-fluid w-100 h-100 object-fit-cover project-thumbnail-img"
                                             width="400"
                                             height="200"
                                             loading="lazy"
                                             decoding="async"
                                             role="button"
                                             tabindex="0"
                                             data-lightbox-trigger
                                             data-image-src="{{ asset('storage/' . $item->path) }}"
                                             data-image-title="{{ $item->caption ?: $project->title }}"
                                             data-image-desc="{{ $item->caption }}"
                                             data-image-download="{{ route('portfolio.media.download', $item->id) }}"
                                             data-image-filename="{{ Str::slug($item->caption ?: 'gallery-'.$item->id) }}">
                                        
                                        <div class="project-thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                            <button type="button" 
                                                    class="btn-bento-primary py-1 px-2 small btn-lightbox-open"
                                                    data-image-src="{{ asset('storage/' . $item->path) }}"
                                                    data-image-title="{{ $item->caption ?: $project->title }}"
                                                    data-image-desc="{{ $item->caption }}"
                                                    data-image-download="{{ route('portfolio.media.download', $item->id) }}"
                                                    data-image-filename="{{ Str::slug($item->caption ?: 'gallery-'.$item->id) }}">
                                                <i class="bi bi-eye-fill"></i> View
                                            </button>
                                            <a href="{{ route('portfolio.media.download', $item->id) }}" class="btn-bento-amber py-1 px-2 small">
                                                <i class="bi bi-download"></i> Unduh
                                            </a>
                                        </div>
                                    </div>
                                    @if($item->caption)
                                        <p class="small text-muted text-center mt-2 mb-1">{{ $item->caption }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Bento Metadata --}}
        <div class="col-lg-4">
            <div class="vstack gap-4">
                <!-- Informasi Proyek -->
                <div class="bento-detail-card p-4 bg-white">
                    <h5 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-info-circle-fill"></i>
                        </span>
                        Informasi Proyek
                    </h5>
                    <ul class="list-unstyled mb-0 vstack gap-2 small">
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Peran:</span>
                            <span class="fw-bold text-dark">{{ $project->role ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">{{ $project->status?->label() }}</span>
                        </li>
                        @if($project->started_at)
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Periode:</span>
                                <span class="fw-bold text-dark">{{ $project->started_at->format('M Y') }} - {{ $project->finished_at ? $project->finished_at->format('M Y') : 'Selesai' }}</span>
                            </li>
                        @endif
                        @if($project->documentations && $project->documentations->count())
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Blueprint &amp; ERD:</span>
                                <a href="#blueprint-terkait" class="badge bg-primary text-white rounded-pill text-decoration-none">
                                    {{ $project->documentations->count() }} Dokumen Terkait ↓
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="pt-4 vstack gap-2">
                        @if($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-primary w-100">
                                <span>Buka Live Demo</span> <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        @endif
                        @if($project->repo_url)
                            <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-secondary w-100">
                                <i class="bi bi-github"></i> <span>Lihat Source Code</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Tech Stack Bento Card -->
                <div class="bento-detail-card p-4 bg-white">
                    <h5 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-stack"></i>
                        </span>
                        Teknologi &amp; Tools
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project->technologies as $tech)
                            <span class="bento-badge-pill bento-badge-blue">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Dokumentasi & Blueprint Terkait Proyek -->
                @if($project->documentations && $project->documentations->count())
                    <div id="blueprint-terkait" class="bento-detail-card p-4 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </span>
                                <h5 class="font-heading mb-0 text-dark">Blueprint &amp; Diagram</h5>
                            </div>
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1 small">
                                {{ $project->documentations->count() }} Artefak
                            </span>
                        </div>
                        <p class="text-muted small mb-3">
                            Spesifikasi teknis, skema ERD database, dan blueprint UML sistem yang terhubung langsung dengan proyek ini.
                        </p>

                        <div class="vstack gap-3">
                            @foreach($project->documentations as $doc)
                                @php
                                    $hasDownloadFile = $doc->hasFiles();
                                    $fileCount = $doc->getFileCount();
                                @endphp
                                <div class="p-3 rounded-4 bg-light border d-flex flex-column" style="border-color: rgba(10, 27, 61, 0.08) !important;">
                                    <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                                        <span class="bento-badge-pill bento-badge-blue py-0 px-2" style="font-size: 0.72rem;">
                                            {{ $doc->category }}
                                        </span>
                                        <div class="d-flex gap-1">
                                            @if($fileCount > 1)
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-0 small fw-bold" style="font-size: 0.7rem;">
                                                    <i class="bi bi-files"></i> {{ $fileCount }}
                                                </span>
                                            @endif
                                            <span class="badge bg-white text-muted border rounded-pill px-2 py-0 small">
                                                <i class="bi {{ $doc->type->icon() }}"></i> {{ $doc->getFileExtension() ? strtoupper($doc->getFileExtension()) : $doc->type->label() }}
                                            </span>
                                        </div>
                                    </div>

                                    <h6 class="font-heading fs-6 mb-2">
                                        <a href="{{ route('portfolio.documentations.show', $doc->slug) }}" class="text-decoration-none text-dark hover-primary">
                                            {{ $doc->title }}
                                        </a>
                                    </h6>

                                    @if($doc->summary)
                                        <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.82rem;">
                                            {{ Str::limit($doc->summary, 85) }}
                                        </p>
                                    @endif

                                    <div class="d-flex align-items-center gap-2 pt-2 border-top mt-auto">
                                        <a href="{{ route('portfolio.documentations.show', $doc->slug) }}" class="btn-bento-primary py-1 px-3 small flex-grow-1 text-center">
                                            <span>Detail Dokumen</span> <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                        @if($hasDownloadFile)
                                            <a href="{{ route('portfolio.documentations.download', $doc->slug) }}" class="btn-bento-amber py-1 px-2 small" title="{{ $fileCount > 1 ? 'Unduh Semua (' . $fileCount . ' Berkas .ZIP)' : 'Unduh Berkas' }}">
                                                <i class="bi {{ $fileCount > 1 ? 'bi-file-zip' : 'bi-download' }}"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
