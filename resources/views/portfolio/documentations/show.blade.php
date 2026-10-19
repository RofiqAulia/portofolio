@extends('layouts.app')

@section('title', $documentation->title . ' — Dokumentasi & Blueprint')
@section('meta_description', Str::limit(strip_tags($documentation->summary ?? $documentation->title), 150))

@section('content')
<div class="container py-4">

    <!-- Top Navigation / Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('portfolio.documentations.index') }}" class="btn-genz btn-genz-secondary py-1 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Semua Dokumentasi
        </a>
    </div>

    @php
        $previewUrl = $documentation->getEffectivePreviewUrl();
        $hasDownloadFile = $documentation->file_path && Storage::disk('public')->exists($documentation->file_path);
    @endphp

    <div class="row gy-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card-genz p-4 p-md-5 bg-white mb-4">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="sticker sticker-blue">{{ $documentation->category }}</span>
                    @if($documentation->is_featured)
                        <span class="sticker sticker-yellow">★ BLUEPRINT UNGGULAN</span>
                    @endif
                    <span class="badge bg-dark text-white rounded-pill px-3 py-1 small">
                        <i class="bi {{ $documentation->type->icon() }}"></i> {{ $documentation->type->label() }}
                    </span>
                </div>

                <h1 class="display-5 font-heading mb-3">{{ $documentation->title }}</h1>

                @if($documentation->summary)
                    <p class="fs-5 text-muted mb-4 lead">
                        {{ $documentation->summary }}
                    </p>
                @endif

                <!-- Visual Diagram / Image Preview Stage -->
                @if($previewUrl)
                    <div class="mb-4">
                        <div class="project-thumbnail-box position-relative overflow-hidden rounded-4 border border-2 border-dark">
                            <img src="{{ $previewUrl }}" 
                                 alt="{{ $documentation->title }}" 
                                 class="img-fluid w-100 object-fit-contain project-thumbnail-img" 
                                 style="max-height: 520px; background-color: #F8FAFD;"
                                 role="button"
                                 tabindex="0"
                                 aria-label="Lihat gambar resolusi penuh"
                                 data-lightbox-trigger
                                 data-image-src="{{ $previewUrl }}"
                                 data-image-title="{{ $documentation->title }}"
                                 data-image-desc="{{ $documentation->summary }}"
                                 data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $documentation->slug) : $previewUrl }}"
                                 data-image-filename="{{ $documentation->getDownloadFilename() }}">

                            <!-- Hover Overlay -->
                            <div class="project-thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                <button type="button" 
                                        class="btn-genz btn-genz-primary py-2 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ $previewUrl }}"
                                        data-image-title="{{ $documentation->title }}"
                                        data-image-desc="{{ $documentation->summary }}"
                                        data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $documentation->slug) : $previewUrl }}"
                                        data-image-filename="{{ $documentation->getDownloadFilename() }}">
                                    <i class="bi bi-arrows-fullscreen"></i> View Resolusi Penuh
                                </button>
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-genz btn-genz-yellow py-2 px-3 fs-6">
                                        <i class="bi bi-download"></i> Unduh Berkas
                                    </a>
                                @endif
                            </div>

                            <!-- Mobile Quick Action -->
                            <div class="position-absolute bottom-0 end-0 m-3 d-md-none d-flex gap-2">
                                <button type="button" 
                                        class="btn-genz btn-genz-primary py-1 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ $previewUrl }}"
                                        data-image-title="{{ $documentation->title }}"
                                        data-image-desc="{{ $documentation->summary }}"
                                        data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $documentation->slug) : $previewUrl }}"
                                        data-image-filename="{{ $documentation->getDownloadFilename() }}">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-genz btn-genz-yellow py-1 px-3 fs-6">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-muted small">
                                <i class="bi bi-zoom-in text-primary me-1"></i> Klik gambar di atas untuk membuka modal zoom resolusi penuh.
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Detailed Description / Architecture Notes -->
                @if($documentation->description)
                    <div class="prose py-3 border-top border-bottom border-2 border-light mb-4">
                        <h4 class="font-heading mb-3">Catatan Arsitektur & Spesifikasi</h4>
                        <div class="fs-6 leading-relaxed">
                            {!! $documentation->description !!}
                        </div>
                    </div>
                @endif

                <!-- Download Callout Box -->
                @if($hasDownloadFile || $documentation->external_url)
                    <div class="card-genz-blue p-4 rounded-4 mt-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div>
                                <h5 class="font-heading mb-1">Dapatkan Salinan Asli Dokumen</h5>
                                <p class="text-muted small mb-0">Unduh diagram / dokumen spesifikasi teknis ini langsung ke perangkat Anda.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-genz btn-genz-yellow py-2 px-3 fs-6">
                                        <i class="bi bi-download"></i> Unduh File Asli
                                    </a>
                                @endif
                                @if($documentation->external_url)
                                    <a href="{{ $documentation->external_url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary py-2 px-3 fs-6">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka Tautan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <div class="vstack gap-4">
                <!-- Info Box -->
                <div class="card-genz p-4 bg-white">
                    <h5 class="font-heading mb-3">Informasi Artefak</h5>
                    <ul class="list-unstyled mb-0 vstack gap-2 small">
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Kategori:</span>
                            <span class="fw-bold">{{ $documentation->category }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Tipe Artefak:</span>
                            <span class="badge bg-primary rounded-pill">{{ $documentation->type->label() }}</span>
                        </li>
                        @if($documentation->getFileExtension())
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Format Ekstensi:</span>
                                <span class="badge bg-dark rounded-pill">{{ strtoupper($documentation->getFileExtension()) }}</span>
                            </li>
                        @endif
                        @if($documentation->published_at)
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Waktu Terbit:</span>
                                <span class="fw-bold">{{ $documentation->published_at->format('d M Y') }}</span>
                            </li>
                        @endif
                        @if($documentation->project)
                            <li class="d-flex flex-column border-bottom pb-2">
                                <span class="text-muted mb-1">Terkait Proyek:</span>
                                <a href="{{ route('portfolio.project.show', $documentation->project->slug) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $documentation->project->title }} <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="pt-4 vstack gap-2">
                        @if($hasDownloadFile)
                            <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-genz btn-genz-yellow w-100 py-2 fs-6">
                                <i class="bi bi-download"></i> Unduh Berkas Lengkap
                            </a>
                        @endif
                        @if($previewUrl)
                            <button type="button" 
                                    class="btn-genz btn-genz-primary w-100 py-2 fs-6 btn-lightbox-open"
                                    data-image-src="{{ $previewUrl }}"
                                    data-image-title="{{ $documentation->title }}"
                                    data-image-desc="{{ $documentation->summary }}"
                                    data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $documentation->slug) : $previewUrl }}"
                                    data-image-filename="{{ $documentation->getDownloadFilename() }}">
                                <i class="bi bi-eye"></i> Buka Preview Lightbox
                            </button>
                        @endif
                        @if($documentation->external_url)
                            <a href="{{ $documentation->external_url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary w-100 py-2 fs-6">
                                <i class="bi bi-box-arrow-up-right"></i> Buka Tautan Eksternal
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Terkait Proyek Box -->
                @if($documentation->project)
                    <div class="card-genz p-4 bg-white border border-2 border-dark" style="box-shadow: 4px 4px 0px var(--navy) !important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary text-white rounded-circle p-2">
                                <i class="bi bi-briefcase-fill"></i>
                            </span>
                            <h5 class="font-heading mb-0">Proyek Terkait</h5>
                        </div>
                        <p class="text-muted small mb-3">Blueprint ini merupakan spesifikasi teknis dari proyek berikut:</p>
                        
                        <div class="border border-2 border-dark rounded-3 p-3 bg-light mb-3">
                            <h6 class="font-heading mb-1 text-dark">{{ $documentation->project->title }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($documentation->project->summary, 100) }}</p>
                            @if($documentation->project->role)
                                <span class="badge bg-white text-dark border border-dark rounded-pill px-2 py-1 small">
                                    Peran: {{ $documentation->project->role }}
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('portfolio.project.show', $documentation->project->slug) }}" class="btn-genz btn-genz-primary w-100 py-2 fs-6">
                            Buka Detail Proyek <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif

                <!-- Related Documents -->
                @if($relatedDocs->count())
                    <div class="card-genz p-4 bg-white">
                        <h5 class="font-heading mb-3">Dokumentasi Terkait</h5>
                        <div class="vstack gap-3">
                            @foreach($relatedDocs as $rel)
                                <a href="{{ route('portfolio.documentations.show', $rel->slug) }}" class="text-decoration-none text-dark p-2 rounded-3 border border-2 border-dark card-genz d-block" style="box-shadow: 3px 3px 0px var(--navy) !important;">
                                    <span class="badge bg-primary text-white rounded-pill px-2 py-0 small mb-1" style="font-size: 0.7rem;">
                                        {{ $rel->category }}
                                    </span>
                                    <h6 class="font-heading mb-1 text-truncate">{{ $rel->title }}</h6>
                                    <span class="text-muted small"><i class="bi bi-arrow-right"></i> Lihat Blueprint</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
