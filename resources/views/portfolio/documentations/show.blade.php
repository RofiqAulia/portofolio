@extends('layouts.app')

@section('title', $documentation->title . ' — Dokumentasi & Blueprint')
@section('meta_description', Str::limit(strip_tags($documentation->summary ?? $documentation->title), 150))

@section('content')
<div class="container py-4">

    <!-- Top Navigation / Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('portfolio.documentations.index') }}" class="btn-bento-secondary py-2 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Semua Dokumentasi
        </a>
    </div>

    @php
        $fileItems = $documentation->getFileItems();
        $hasDownloadFile = !empty($fileItems);
        $firstFile = $fileItems[0] ?? null;
        $isPdf = $firstFile && $firstFile['extension'] === 'pdf';
        $previewUrl = $documentation->getEffectivePreviewUrl();
    @endphp

    <div class="row gy-4">
        <!-- Main Content (Bento Showcase) -->
        <div class="col-lg-8">
            <div class="bento-detail-card p-4 p-md-5 bg-white mb-4">
                <!-- Badges -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="bento-badge-pill bento-badge-blue">
                        <i class="bi bi-folder-fill text-primary"></i> {{ $documentation->category }}
                    </span>
                    @if($documentation->is_featured)
                        <span class="bento-badge-pill bento-badge-amber">
                            <i class="bi bi-star-fill text-warning"></i> BLUEPRINT UNGGULAN
                        </span>
                    @endif
                    <span class="badge bg-dark text-white rounded-pill px-3 py-1 small">
                        <i class="bi {{ $documentation->type->icon() }}"></i> {{ $documentation->type->label() }}
                    </span>
                </div>

                <h1 class="display-5 font-heading mb-3 text-dark">{{ $documentation->title }}</h1>

                @if($documentation->summary)
                    <p class="fs-5 text-muted mb-4 lead">
                        {{ $documentation->summary }}
                    </p>
                @endif

                <!-- Jika Berkas Utama adalah PDF: Embed Pratinjau Dokumen -->
                @if($isPdf)
                    <div class="pdf-preview-box p-3 p-sm-4 mb-4">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                            <div class="d-flex align-items-center gap-2.5 gap-sm-3 min-w-0 flex-grow-1">
                                <span class="badge bg-danger text-white pdf-icon-badge shadow-sm">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </span>
                                <div class="min-w-0 flex-grow-1">
                                    <span class="badge bg-dark text-white rounded-pill px-2.5 py-1 small fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                                        DOKUMEN SPESIFIKASI (PDF)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3 overflow-hidden bg-white shadow-sm border pdf-viewer-frame position-relative" style="border-color: rgba(10, 27, 61, 0.08) !important;">
                            <iframe src="{{ route('portfolio.documentations.preview', $documentation->slug) }}" 
                                    class="w-100 h-100 border-0" 
                                    title="Pratinjau PDF {{ $documentation->title }}"
                                    loading="lazy"></iframe>
                        </div>

                        <!-- Mobile-Friendly Helper Bar -->
                        <div class="d-flex align-items-center justify-content-between pt-2 px-1 text-muted small mt-1">
                            <span class="d-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                                <i class="bi bi-info-circle text-primary"></i>
                                <span class="d-none d-sm-inline">Pratinjau dokumen interaktif. Gunakan "Buka Layar Penuh" untuk navigasi &amp; zoom lebih leluasa.</span>
                                <span class="d-sm-none">Ketuk "Layar Penuh" untuk membaca lebih leluasa.</span>
                            </span>
                            <a href="{{ route('portfolio.documentations.preview', $documentation->slug) }}" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-bold small flex-shrink-0 d-sm-none" style="font-size: 0.78rem;">
                                Buka <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                @elseif($previewUrl)
                    <!-- Visual Diagram / Image Preview Stage -->
                    <div class="mb-4">
                        <div class="project-thumbnail-box position-relative overflow-hidden rounded-4 border" style="border-color: rgba(10, 27, 61, 0.08) !important; box-shadow: 0 10px 30px rgba(10, 27, 61, 0.06);">
                            <img src="{{ $previewUrl }}" 
                                 alt="{{ $documentation->title }}" 
                                 class="img-fluid w-100 object-fit-contain project-thumbnail-img" 
                                 style="max-height: 520px; background-color: #F8FAFD;"
                                 width="800"
                                 height="500"
                                 fetchpriority="high"
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
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.preview', $documentation->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-bento-primary py-2 px-3 fs-6">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka File Asli
                                    </a>
                                @endif
                                <button type="button" 
                                        class="btn-bento-secondary py-2 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ $previewUrl }}"
                                        data-image-title="{{ $documentation->title }}"
                                        data-image-desc="{{ $documentation->summary }}"
                                        data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $documentation->slug) : $previewUrl }}"
                                        data-image-filename="{{ $documentation->getDownloadFilename() }}">
                                    <i class="bi bi-zoom-in"></i> Zoom Lightbox
                                </button>
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-bento-amber py-2 px-3 fs-6">
                                        <i class="bi bi-download"></i> Unduh Berkas
                                    </a>
                                @endif
                            </div>

                            <!-- Mobile Quick Action -->
                            <div class="position-absolute bottom-0 end-0 m-3 d-md-none d-flex gap-2">
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.preview', $documentation->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-bento-primary py-1 px-3 fs-6">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka File
                                    </a>
                                @endif
                                <button type="button" 
                                        class="btn-bento-secondary py-1 px-3 fs-6 btn-lightbox-open"
                                        data-image-src="{{ $previewUrl }}"
                                        data-image-title="{{ $documentation->title }}"
                                        data-image-desc="{{ $documentation->summary }}"
                                        data-image-download="{{ $hasDownloadFile ? route('portfolio.documentations.download', $documentation->slug) : $previewUrl }}"
                                        data-image-filename="{{ $documentation->getDownloadFilename() }}">
                                    <i class="bi bi-eye"></i> Zoom
                                </button>
                                @if($hasDownloadFile)
                                    <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-bento-amber py-1 px-3 fs-6">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-muted small">
                                <i class="bi bi-zoom-in text-primary me-1"></i> Klik diagram di atas untuk melihat dalam resolusi penuh &amp; mode zoom.
                            </span>
                        </div>

                        @php
                            $imageItems = collect($fileItems)->filter(fn($f) => $f['is_image'])->values();
                        @endphp
                        @if($imageItems->count() > 1)
                            <div class="pt-3 border-top mt-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-bold text-dark">
                                        <i class="bi bi-images text-primary me-1"></i> Lembar Diagram Lainnya ({{ $imageItems->count() }} Lembar):
                                    </span>
                                    <span class="text-muted small">Klik untuk membuka</span>
                                </div>
                                <div class="row g-2">
                                    @foreach($imageItems as $imgItem)
                                        <div class="col-4 col-sm-3 col-md-2">
                                            <div class="project-thumbnail-box rounded-3 border overflow-hidden position-relative" style="height: 72px; border-color: rgba(10, 27, 61, 0.1) !important;">
                                                <img src="{{ $imgItem['url'] }}" 
                                                     alt="{{ $imgItem['name'] }}"
                                                     class="w-100 h-100 object-fit-cover btn-lightbox-open"
                                                     width="120"
                                                     height="72"
                                                     loading="lazy"
                                                     decoding="async"
                                                     role="button"
                                                     tabindex="0"
                                                     data-image-src="{{ $imgItem['url'] }}"
                                                     data-image-title="{{ $documentation->title }} (Lembar {{ $imgItem['index'] + 1 }})"
                                                     data-image-desc="{{ $documentation->summary }}"
                                                     data-image-download="{{ route('portfolio.documentations.download', ['slug' => $documentation->slug, 'file' => $imgItem['index']]) }}"
                                                     data-image-filename="{{ $documentation->getDownloadFilename($imgItem['index']) }}"
                                                     title="Lihat Lembar #{{ $imgItem['index'] + 1 }}: {{ $imgItem['name'] }}">
                                                <span class="position-absolute bottom-0 start-0 m-1 badge bg-dark bg-opacity-75 text-white" style="font-size: 0.65rem;">
                                                    #{{ $imgItem['index'] + 1 }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Detailed Description / Architecture Notes -->
                @if($documentation->description)
                    <div class="prose py-3 border-top border-bottom my-4">
                        <h4 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                                <i class="bi bi-diagram-3-fill"></i>
                            </span>
                            Catatan Arsitektur &amp; Spesifikasi
                        </h4>
                        <div class="fs-6 leading-relaxed text-dark" style="color: #000000 !important;">
                            {!! $documentation->description !!}
                        </div>
                    </div>
                @endif

                <!-- Download Callout Box (Royal Blue Bento Style) -->
                @if($hasDownloadFile || $documentation->external_url)
                    <div class="p-4 rounded-4 mt-4" style="background: linear-gradient(145deg, #FFFFFF 0%, #F1F6FD 100%); border: 1px solid rgba(14, 165, 233, 0.2); box-shadow: 0 4px 20px rgba(10, 27, 61, 0.04);">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                <div>
                                    <h5 class="font-heading mb-1 text-dark d-flex align-items-center gap-2">
                                        <i class="bi bi-cloud-arrow-down text-primary"></i> Salinan Asli Dokumen
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        @if(count($fileItems) > 1)
                                            Tersedia {{ count($fileItems) }} berkas spesifikasi teknis dan blueprint untuk diunduh langsung.
                                        @else
                                            Unduh diagram / dokumen spesifikasi teknis ini langsung ke perangkat Anda.
                                        @endif
                                    </p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($hasDownloadFile)
                                        <a href="{{ route('portfolio.documentations.preview', $documentation->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-bento-primary py-2 px-3 fs-6">
                                            <i class="bi bi-box-arrow-up-right"></i> Buka Preview
                                        </a>
                                    @endif
                                    @if(count($fileItems) > 1)
                                        <a href="{{ route('portfolio.documentations.download', ['slug' => $documentation->slug, 'all' => 1]) }}" class="btn-bento-amber py-2 px-3 fs-6">
                                            <i class="bi bi-file-zip"></i> Unduh Semua (.ZIP)
                                        </a>
                                    @elseif(count($fileItems) === 1)
                                        <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-bento-amber py-2 px-3 fs-6">
                                            <i class="bi bi-download"></i> Unduh Berkas
                                        </a>
                                    @endif
                                    @if($documentation->external_url)
                                        <a href="{{ $documentation->external_url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-secondary py-2 px-3 fs-6">
                                            <i class="bi bi-box-arrow-up-right"></i> Tautan Eksternal
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if(count($fileItems) > 1)
                                <div class="border-top pt-3 mt-1">
                                    <span class="small fw-bold text-dark d-block mb-2">
                                        <i class="bi bi-files text-primary me-1"></i> Rincian Berkas Terlampir ({{ count($fileItems) }} Berkas):
                                    </span>
                                    <div class="vstack gap-2">
                                        @foreach($fileItems as $file)
                                            <div class="bg-white p-2 px-3 rounded-3 border d-flex align-items-center justify-content-between gap-2" style="border-color: rgba(10, 27, 61, 0.08) !important;">
                                                <div class="d-flex align-items-center gap-2 text-truncate">
                                                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1 small">
                                                        #{{ $file['index'] + 1 }}
                                                    </span>
                                                    <i class="bi {{ $file['is_image'] ? 'bi-image text-primary' : ($file['extension'] === 'pdf' ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark text-primary') }} fs-5"></i>
                                                    <span class="small fw-bold text-dark text-truncate">{{ $file['name'] }}</span>
                                                    <span class="badge bg-light text-muted border rounded-pill small">{{ strtoupper($file['extension'] ?: 'FILE') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                                    <a href="{{ route('portfolio.documentations.preview', ['slug' => $documentation->slug, 'file' => $file['index']]) }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer" 
                                                       class="btn-bento-primary py-1 px-2 small" 
                                                       title="Buka / Preview Berkas Ini">
                                                        <i class="bi bi-box-arrow-up-right"></i> Buka
                                                    </a>
                                                    @if($file['is_image'])
                                                        <button type="button" 
                                                                class="btn-bento-secondary py-1 px-2 small btn-lightbox-open"
                                                                data-image-src="{{ $file['url'] }}"
                                                                data-image-title="{{ $documentation->title }} — Lembar #{{ $file['index'] + 1 }}"
                                                                data-image-desc="{{ $documentation->summary }}"
                                                                data-image-download="{{ route('portfolio.documentations.download', ['slug' => $documentation->slug, 'file' => $file['index']]) }}"
                                                                data-image-filename="{{ $documentation->getDownloadFilename($file['index']) }}"
                                                                title="Zoom Modal">
                                                            <i class="bi bi-zoom-in"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('portfolio.documentations.download', ['slug' => $documentation->slug, 'file' => $file['index']]) }}" class="btn-bento-amber py-1 px-2 small" title="Unduh Berkas Ini">
                                                        <i class="bi bi-download"></i> Unduh
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar (Bento Metadata) -->
        <div class="col-lg-4">
            <div class="vstack gap-4">
                <!-- Info Box -->
                <div class="bento-detail-card p-4 bg-white">
                    <h5 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-info-circle-fill"></i>
                        </span>
                        Informasi Artefak
                    </h5>
                    <ul class="list-unstyled mb-0 vstack gap-2 small">
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Kategori:</span>
                            <span class="fw-bold text-dark">{{ $documentation->category }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Tipe Artefak:</span>
                            <span class="badge bg-primary rounded-pill">{{ $documentation->type->label() }}</span>
                        </li>
                        @if(count($fileItems) > 1)
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Jumlah Berkas:</span>
                                <span class="badge bg-dark rounded-pill">{{ count($fileItems) }} Berkas</span>
                            </li>
                        @endif
                        @if($documentation->getFileExtension())
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Format Ekstensi:</span>
                                <span class="badge bg-light text-muted border rounded-pill">{{ strtoupper($documentation->getFileExtension()) }}</span>
                            </li>
                        @endif
                        @if($documentation->published_at)
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Waktu Terbit:</span>
                                <span class="fw-bold text-dark">{{ $documentation->published_at->format('d M Y') }}</span>
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
                            <a href="{{ route('portfolio.documentations.preview', $documentation->slug) }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn-bento-primary w-100 py-2 fs-6">
                                <i class="bi bi-box-arrow-up-right"></i> Buka Preview Berkas
                            </a>
                            @if(count($fileItems) > 1)
                                <a href="{{ route('portfolio.documentations.download', ['slug' => $documentation->slug, 'all' => 1]) }}" class="btn-bento-amber w-100 py-2 fs-6">
                                    <i class="bi bi-file-zip"></i> Unduh Semua (.ZIP)
                                </a>
                            @else
                                <a href="{{ route('portfolio.documentations.download', $documentation->slug) }}" class="btn-bento-amber w-100 py-2 fs-6">
                                    <i class="bi bi-download"></i> Unduh Berkas
                                </a>
                            @endif
                        @elseif($previewUrl)
                            <button type="button" 
                                    class="btn-bento-primary w-100 py-2 fs-6 btn-lightbox-open"
                                    data-image-src="{{ $previewUrl }}"
                                    data-image-title="{{ $documentation->title }}"
                                    data-image-desc="{{ $documentation->summary }}"
                                    data-image-download="{{ $previewUrl }}"
                                    data-image-filename="{{ $documentation->getDownloadFilename() }}">
                                <i class="bi bi-eye"></i> Buka Preview Berkas
                            </button>
                        @endif
                        @if($documentation->external_url)
                            <a href="{{ $documentation->external_url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-secondary w-100 py-2 fs-6">
                                <i class="bi bi-box-arrow-up-right"></i> Buka Tautan Eksternal
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Terkait Proyek Box -->
                @if($documentation->project)
                    <div class="bento-detail-card p-4 bg-white">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                                <i class="bi bi-briefcase-fill"></i>
                            </span>
                            <h5 class="font-heading mb-0 text-dark">Proyek Terkait</h5>
                        </div>
                        <p class="text-muted small mb-3">Blueprint ini merupakan spesifikasi teknis dari proyek berikut:</p>
                        
                        <div class="rounded-3 p-3 bg-light border mb-3" style="border-color: rgba(10, 27, 61, 0.08) !important;">
                            <h6 class="font-heading mb-1 text-dark">{{ $documentation->project->title }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($documentation->project->summary, 100) }}</p>
                            @if($documentation->project->role)
                                <span class="badge bg-white text-muted border rounded-pill px-2 py-1 small">
                                    Peran: {{ $documentation->project->role }}
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('portfolio.project.show', $documentation->project->slug) }}" class="btn-bento-primary w-100 py-2 fs-6">
                            <span>Buka Detail Proyek</span> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif

                <!-- Related Documents -->
                @if($relatedDocs->count())
                    <div class="bento-detail-card p-4 bg-white">
                        <h5 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                                <i class="bi bi-folder2-open"></i>
                            </span>
                            Dokumentasi Terkait
                        </h5>
                        <div class="vstack gap-2">
                            @foreach($relatedDocs as $rel)
                                <a href="{{ route('portfolio.documentations.show', $rel->slug) }}" class="text-decoration-none text-dark p-3 rounded-3 border bg-light d-block transition-all hover-translate" style="border-color: rgba(10, 27, 61, 0.08) !important;">
                                    <span class="bento-badge-pill bento-badge-blue py-0 px-2 small mb-1" style="font-size: 0.7rem;">
                                        {{ $rel->category }}
                                    </span>
                                    <h6 class="font-heading mb-1 text-truncate text-dark">{{ $rel->title }}</h6>
                                    <span class="text-primary small fw-bold">Lihat Blueprint <i class="bi bi-arrow-right"></i></span>
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
