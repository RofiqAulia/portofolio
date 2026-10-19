@extends('layouts.app')

@section('title', $project->title . ' — Portofolio')
@section('meta_description', Str::limit(strip_tags($project->summary), 150))

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('portfolio.home') }}#proyek" class="btn-genz btn-genz-secondary py-1 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card-genz p-4 p-md-5 bg-white mb-4">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @if($project->is_featured)
                        <span class="sticker sticker-yellow">★ PROYEK UNGGULAN</span>
                    @endif
                    <span class="sticker sticker-blue">{{ $project->role ?? 'Software Developer' }}</span>
                </div>

                <h1 class="display-5 font-heading mb-3">{{ $project->title }}</h1>

                <p class="fs-5 text-muted mb-4 lead">
                    {{ $project->summary }}
                </p>

                @if($project->thumbnail_path)
                    <div class="mb-4">
                        <div class="project-thumbnail-box position-relative overflow-hidden rounded-4 border border-2 border-dark">
                            <img src="{{ asset('storage/' . $project->thumbnail_path) }}" 
                                 alt="{{ $project->title }}" 
                                 class="img-fluid w-100 object-fit-cover project-thumbnail-img" 
                                 style="max-height: 480px;"
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
                                        class="btn-genz btn-genz-primary py-2 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                        data-image-title="{{ $project->title }}"
                                        data-image-desc="{{ $project->summary }}"
                                        data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                                        data-image-filename="{{ Str::slug($project->title) }}-preview">
                                    <i class="bi bi-eye-fill"></i> View Penuh
                                </button>
                                <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" class="btn-genz btn-genz-yellow py-2 px-3 fs-6">
                                    <i class="bi bi-download"></i> Download Gambar
                                </a>
                            </div>

                            <!-- Mobile Quick Actions -->
                            <div class="position-absolute bottom-0 end-0 m-3 d-md-none d-flex gap-2">
                                <button type="button" 
                                        class="btn-genz btn-genz-primary py-1 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                        data-image-title="{{ $project->title }}"
                                        data-image-desc="{{ $project->summary }}"
                                        data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                                        data-image-filename="{{ Str::slug($project->title) }}-preview">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>
                                <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" class="btn-genz btn-genz-yellow py-1 px-3 fs-6">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="prose py-3 border-top border-bottom border-2 border-light mb-4">
                    <h4 class="font-heading mb-3">Cerita & Arsitektur Proyek</h4>
                    <div class="fs-6 leading-relaxed">
                        {!! $project->description ?: nl2br(e($project->summary)) !!}
                    </div>
                </div>

                @if($project->media->count())
                    <h4 class="font-heading mb-3">Galeri Proyek</h4>
                    <div class="row g-3">
                        @foreach($project->media as $item)
                            <div class="col-md-6">
                                <div class="card-genz p-2 bg-light">
                                    <div class="project-thumbnail-box position-relative overflow-hidden rounded-3 border border-2 border-dark" style="height: 200px;">
                                        <img src="{{ asset('storage/' . $item->path) }}" 
                                             alt="{{ $item->caption }}" 
                                             class="img-fluid w-100 h-100 object-fit-cover project-thumbnail-img"
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
                                                    class="btn-genz btn-genz-primary py-1 px-2 small btn-lightbox-open"
                                                    data-image-src="{{ asset('storage/' . $item->path) }}"
                                                    data-image-title="{{ $item->caption ?: $project->title }}"
                                                    data-image-desc="{{ $item->caption }}"
                                                    data-image-download="{{ route('portfolio.media.download', $item->id) }}"
                                                    data-image-filename="{{ Str::slug($item->caption ?: 'gallery-'.$item->id) }}">
                                                <i class="bi bi-eye-fill"></i> View
                                            </button>
                                            <a href="{{ route('portfolio.media.download', $item->id) }}" class="btn-genz btn-genz-yellow py-1 px-2 small">
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

                <!-- Dokumentasi & Blueprint Terkait Proyek -->
                @if($project->documentations && $project->documentations->count())
                    <div id="blueprint-terkait" class="mt-4 pt-4 border-top border-2 border-light">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white rounded-circle p-2">
                                    <i class="bi bi-file-earmark-code"></i>
                                </span>
                                <h4 class="font-heading mb-0">Dokumentasi & Blueprint Sistem Terkait</h4>
                            </div>
                            <span class="badge bg-dark text-white rounded-pill px-3 py-1 small">
                                {{ $project->documentations->count() }} Artefak
                            </span>
                        </div>
                        <p class="text-muted small mb-3">
                            Spesifikasi teknis, rancangan ERD database, dan blueprint arsitektur sistem yang saling terhubung untuk proyek ini.
                        </p>

                        <div class="row g-3">
                            @foreach($project->documentations as $doc)
                                @php
                                    $previewUrl = $doc->getEffectivePreviewUrl();
                                    $hasDownloadFile = $doc->hasFiles();
                                    $fileCount = $doc->getFileCount();
                                @endphp
                                <div class="col-md-6">
                                    <div class="card-genz p-3 bg-white h-100 d-flex flex-column border border-2 border-dark" style="box-shadow: 4px 4px 0px var(--navy) !important;">
                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                                            <span class="sticker sticker-blue py-0 px-2" style="font-size: 0.72rem;">
                                                {{ $doc->category }}
                                            </span>
                                            <div class="d-flex gap-1">
                                                @if($fileCount > 1)
                                                    <span class="badge bg-primary text-white border border-dark rounded-pill px-2 py-0 small fw-bold" style="font-size: 0.7rem;">
                                                        <i class="bi bi-files"></i> {{ $fileCount }}
                                                    </span>
                                                @endif
                                                <span class="badge bg-light text-dark border border-dark rounded-pill px-2 py-0 small">
                                                    <i class="bi {{ $doc->type->icon() }}"></i> {{ $doc->getFileExtension() ? strtoupper($doc->getFileExtension()) : $doc->type->label() }}
                                                </span>
                                            </div>
                                        </div>

                                        <h5 class="font-heading fs-6 mb-2">
                                            <a href="{{ route('portfolio.documentations.show', $doc->slug) }}" class="text-decoration-none text-dark">
                                                {{ $doc->title }}
                                            </a>
                                        </h5>

                                        @if($doc->summary)
                                            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.82rem;">
                                                {{ Str::limit($doc->summary, 90) }}
                                            </p>
                                        @endif

                                        <div class="d-flex align-items-center gap-2 pt-2 border-top border-light mt-auto">
                                            <a href="{{ route('portfolio.documentations.show', $doc->slug) }}" class="btn-genz btn-genz-primary py-1 px-3 small fs-6">
                                                Detail Dokumen <i class="bi bi-arrow-right-short"></i>
                                            </a>
                                            @if($previewUrl)
                                                <button type="button" 
                                                        class="btn-genz btn-genz-secondary py-1 px-2 small btn-lightbox-open"
                                                        data-image-src="{{ $previewUrl }}"
                                                        data-image-title="{{ $doc->title }}"
                                                        data-image-desc="{{ $doc->summary }}"
                                                        data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $doc->slug) : $previewUrl }}"
                                                        data-image-filename="{{ $doc->getDownloadFilename() }}"
                                                        title="Pratinjau Diagram">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @endif
                                            @if($hasDownloadFile)
                                                <a href="{{ route('portfolio.documentations.download', $doc->slug) }}" class="btn-genz btn-genz-yellow py-1 px-2 small" title="{{ $fileCount > 1 ? 'Unduh Semua (' . $fileCount . ' Berkas .ZIP)' : 'Unduh Berkas Asli' }}">
                                                    <i class="bi {{ $fileCount > 1 ? 'bi-file-zip' : 'bi-download' }}"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="vstack gap-4">
                <div class="card-genz p-4 bg-white">
                    <h5 class="font-heading mb-3">Informasi Proyek</h5>
                    <ul class="list-unstyled mb-0 vstack gap-2 small">
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Peran:</span>
                            <span class="fw-bold">{{ $project->role ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-success rounded-pill">{{ $project->status?->label() }}</span>
                        </li>
                        @if($project->started_at)
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Periode:</span>
                                <span class="fw-bold">{{ $project->started_at->format('M Y') }} - {{ $project->finished_at ? $project->finished_at->format('M Y') : 'Selesai' }}</span>
                            </li>
                        @endif
                        @if($project->documentations && $project->documentations->count())
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Blueprint & ERD:</span>
                                <a href="#blueprint-terkait" class="badge bg-primary text-white rounded-pill text-decoration-none">
                                    {{ $project->documentations->count() }} Dokumen Terkait ↓
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="pt-4 vstack gap-2">
                        @if($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-primary w-100">
                                Buka Live Demo <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        @endif
                        @if($project->repo_url)
                            <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary w-100">
                                <i class="bi bi-github"></i> Lihat Source Code
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-genz p-4 bg-white">
                    <h5 class="font-heading mb-3">Tech Stack</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project->technologies as $tech)
                            <span class="pill-tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
