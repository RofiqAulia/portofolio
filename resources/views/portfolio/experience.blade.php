@extends('layouts.app')

@section('title', $experience->position . ' — ' . $experience->organization_name)
@section('meta_description', Str::limit(strip_tags($experience->summary ?? $experience->story ?? ''), 150))

@section('content')
<div class="container py-4">
    <!-- Back Navigation -->
    <div class="mb-4">
        <a href="{{ route('portfolio.home') }}#{{ $experience->type?->value === 'internship' ? 'magang' : 'organisasi' }}" class="btn-bento-secondary py-2 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    @php
        $isInternship = $experience->type?->value === 'internship';
    @endphp

    <div class="row gy-4">
        {{-- Main Experience Detail (Bento Showcase) --}}
        <div class="col-lg-8">
            <div class="bento-detail-card p-4 p-md-5 bg-white mb-4">
                <!-- Badges Row -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 fw-bold">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $experience->start_date?->format('M Y') }} — {{ $experience->is_current ? 'Sekarang' : ($experience->end_date ? $experience->end_date->format('M Y') : '-') }}
                    </span>

                    @if($experience->location)
                        <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $experience->location }}
                        </span>
                    @endif
                </div>

                <!-- Position & Company Spotlight Stage -->
                @if($isInternship)
                    <div class="internship-logo-stage p-4 rounded-4 mb-4">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                            <div class="internship-logo-box flex-shrink-0" style="width: 104px; height: 104px; border-radius: 50%;">
                                @if($experience->cover_path)
                                    <img src="{{ asset('storage/' . $experience->cover_path) }}" alt="{{ $experience->organization_name }}" class="internship-logo-img" style="border-radius: 50%;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light rounded-circle">
                                        <i class="bi bi-buildings-fill text-warning fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <span class="bento-badge-pill bento-badge-amber">
                                        <i class="bi bi-buildings-fill me-1"></i> PENGALAMAN INDUSTRI RIIL
                                    </span>
                                    <span class="badge bg-white text-dark border rounded-pill px-3 py-1 small fw-bold shadow-sm">
                                        <i class="bi bi-patch-check-fill text-warning me-1"></i> Mitra Terverifikasi
                                    </span>
                                </div>
                                <h1 class="display-6 font-heading mb-1 text-dark fw-bold">{{ $experience->position }}</h1>
                                <h4 class="text-warning-dark fw-bold mb-0 fs-5">{{ $experience->organization_name }}</h4>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="organization-crest-stage p-4 rounded-4 mb-4">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                            <div class="organization-crest-box flex-shrink-0" style="width: 104px; height: 104px; border-radius: 50%;">
                                @if($experience->cover_path)
                                    <img src="{{ asset('storage/' . $experience->cover_path) }}" alt="{{ $experience->organization_name }}" class="organization-crest-img" style="border-radius: 50%;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light rounded-circle">
                                        <i class="bi bi-people-fill fs-1" style="color: #7C3AED;"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <span class="bento-badge-pill bento-badge-purple">
                                        <i class="bi bi-people-fill me-1"></i> KEPEMIMPINAN &amp; ORGANISASI
                                    </span>
                                    <span class="badge bg-white text-dark border rounded-pill px-3 py-1 small fw-bold shadow-sm">
                                        <i class="bi bi-award-fill me-1" style="color: #7C3AED;"></i> Kepengurusan Resmi
                                    </span>
                                </div>
                                <h1 class="display-6 font-heading mb-1 text-dark fw-bold">{{ $experience->position }}</h1>
                                <h4 class="fw-bold mb-0 fs-5" style="color: #7C3AED;">{{ $experience->organization_name }}</h4>
                            </div>
                        </div>
                    </div>
                @endif

                @if($experience->summary)
                    <div class="p-3 {{ $isInternship ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : 'card-genz-blue' }} rounded-4 mb-4">
                        <p class="mb-0 fs-6 fw-medium text-dark">
                            <strong>Ringkasan Peran:</strong> {{ $experience->summary }}
                        </p>
                    </div>
                @endif

                <div class="prose py-3 border-top border-bottom my-4">
                    <h4 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge {{ $isInternship ? 'bg-warning bg-opacity-20 text-warning-dark' : 'bg-primary bg-opacity-10 text-primary' }} rounded-circle p-2 fs-6">
                            <i class="bi bi-card-text"></i>
                        </span>
                        Cerita &amp; Tanggung Jawab
                    </h4>
                    <div class="fs-6 leading-relaxed text-dark" style="color: #000000 !important;">
                        {!! $experience->story ?: nl2br(e($experience->summary)) !!}
                    </div>
                </div>

                @if($experience->highlights->count())
                    <div class="mb-4">
                        <h4 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-2 fs-6">
                                <i class="bi bi-award-fill"></i>
                            </span>
                            Sorotan Pencapaian Utama
                        </h4>
                        <div class="vstack gap-2">
                            @foreach($experience->highlights as $highlight)
                                <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3 border" style="border-color: rgba(10, 27, 61, 0.06) !important;">
                                    <span class="text-success fs-5 mt-n1"><i class="bi bi-check-circle-fill"></i></span>
                                    <span class="fw-medium text-dark">{{ $highlight->content }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($experience->media->count())
                    <h4 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-images"></i>
                        </span>
                        Dokumentasi Media
                    </h4>
                    <div class="row g-3">
                        @foreach($experience->media as $item)
                            <div class="col-md-6">
                                <div class="bento-detail-card p-2 bg-light">
                                    <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->caption }}" class="img-fluid rounded-3 w-100 object-fit-cover" style="height: 200px;">
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
                <!-- Attachments Card -->
                <div class="bento-detail-card p-4 bg-white">
                    <h5 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                            <i class="bi bi-paperclip"></i>
                        </span>
                        Berkas &amp; Bukti Lampiran
                    </h5>
                    <div class="vstack gap-2">
                        @forelse($experience->attachments as $att)
                            @if($att->isFile() && $att->file_path)
                                <a href="{{ asset('storage/' . $att->file_path) }}" download class="{{ $isInternship ? 'btn-bento-amber' : 'btn-bento-purple' }} w-100 justify-content-between py-2 px-3 fs-6">
                                    <span><i class="bi bi-file-earmark-pdf-fill me-1"></i> {{ $att->title }}</span>
                                    <i class="bi bi-download"></i>
                                </a>
                            @elseif($att->isLink() && $att->url)
                                <a href="{{ $att->url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-secondary w-100 justify-content-between py-2 px-3 fs-6">
                                    <span><i class="bi bi-link-45deg me-1"></i> {{ $att->title }}</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        @empty
                            <p class="text-muted small mb-0">Tidak ada lampiran berkas yang diunggah.</p>
                        @endforelse
                    </div>
                </div>

                @if($experience->technologies->count())
                    <div class="bento-detail-card p-4 bg-white">
                        <h5 class="font-heading mb-3 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                                <i class="bi bi-stack"></i>
                            </span>
                            Tools &amp; Teknologi
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($experience->technologies as $tech)
                                <span class="bento-badge-pill {{ $isInternship ? 'bento-badge-amber' : 'bento-badge-purple' }}">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
