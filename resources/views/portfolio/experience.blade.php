@extends('layouts.app')

@section('title', $experience->position . ' — ' . $experience->organization_name)
@section('meta_description', Str::limit(strip_tags($experience->summary ?? $experience->story ?? ''), 150))

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('portfolio.home') }}#{{ $experience->type?->value === 'internship' ? 'magang' : 'organisasi' }}" class="btn-genz btn-genz-secondary py-1 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card-genz p-4 p-md-5 bg-white mb-4">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="sticker {{ $experience->type?->value === 'internship' ? 'sticker-blue' : 'sticker-yellow' }}">
                        {{ $experience->type?->label() }}
                    </span>
                    <span class="badge bg-light text-dark border border-2 border-dark rounded-pill px-3 py-2 fw-bold">
                        {{ $experience->start_date?->format('M Y') }} — {{ $experience->is_current ? 'Sekarang' : ($experience->end_date ? $experience->end_date->format('M Y') : '-') }}
                    </span>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="experience-logo-box flex-shrink-0" style="width: 76px; height: 76px;">
                        @if($experience->cover_path)
                            <img src="{{ asset('storage/' . $experience->cover_path) }}" alt="{{ $experience->organization_name }}" class="experience-logo-img">
                        @else
                            <div class="experience-logo-fallback d-flex align-items-center justify-content-center w-100 h-100">
                                @if($experience->type?->value === 'organization')
                                    <i class="bi bi-diagram-2-fill text-warning fs-2"></i>
                                @else
                                    <i class="bi bi-briefcase-fill text-primary fs-2"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="display-5 font-heading mb-1">{{ $experience->position }}</h1>
                        <h4 class="text-primary fw-bold mb-0">{{ $experience->organization_name }}</h4>
                    </div>
                </div>

                @if($experience->summary)
                    <div class="p-3 card-genz-blue rounded-4 mb-4">
                        <p class="mb-0 fs-6 fw-medium">
                            <strong>Ringkasan:</strong> {{ $experience->summary }}
                        </p>
                    </div>
                @endif

                <div class="prose py-3 border-top border-bottom border-2 border-light mb-4">
                    <h4 class="font-heading mb-3">Cerita & Tanggung Jawab</h4>
                    <div class="fs-6 leading-relaxed">
                        {!! $experience->story ?: nl2br(e($experience->summary)) !!}
                    </div>
                </div>

                @if($experience->highlights->count())
                    <div class="mb-4">
                        <h4 class="font-heading mb-3">Sorotan Pencapaian</h4>
                        <ul class="list-group list-group-flush rounded-4 border border-2 border-dark p-2">
                            @foreach($experience->highlights as $highlight)
                                <li class="list-group-item d-flex align-items-center gap-3 border-0 py-2">
                                    <span class="text-primary fs-5"><i class="bi bi-check-circle-fill"></i></span>
                                    <span class="fw-medium">{{ $highlight->content }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($experience->media->count())
                    <h4 class="font-heading mb-3">Dokumentasi Media</h4>
                    <div class="row g-3">
                        @foreach($experience->media as $item)
                            <div class="col-md-6">
                                <div class="card-genz p-2 bg-light">
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

        <div class="col-lg-4">
            <div class="vstack gap-4">
                <!-- Attachments Card -->
                <div class="card-genz p-4 bg-white">
                    <h5 class="font-heading mb-3">Berkas & Bukti Lampiran</h5>
                    <div class="vstack gap-2">
                        @forelse($experience->attachments as $att)
                            @if($att->isFile() && $att->file_path)
                                <a href="{{ asset('storage/' . $att->file_path) }}" download class="btn-genz btn-genz-yellow w-100 justify-content-between">
                                    <span><i class="bi bi-file-earmark-pdf-fill me-1"></i> {{ $att->title }}</span>
                                    <i class="bi bi-download"></i>
                                </a>
                            @elseif($att->isLink() && $att->url)
                                <a href="{{ $att->url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary w-100 justify-content-between">
                                    <span><i class="bi bi-link-45deg me-1"></i> {{ $att->title }}</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        @empty
                            <p class="text-muted small mb-0">Tidak ada lampiran berkas.</p>
                        @endforelse
                    </div>
                </div>

                @if($experience->technologies->count())
                    <div class="card-genz p-4 bg-white">
                        <h5 class="font-heading mb-3">Tools & Teknologi</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($experience->technologies as $tech)
                                <span class="pill-tag">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
