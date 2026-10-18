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
