<div class="card-genz p-4 p-md-5 bg-white h-100 d-flex flex-column">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-3">
        <div class="d-flex align-items-start gap-3">
            <div class="experience-logo-box flex-shrink-0 mt-1">
                @if($experience->cover_path)
                    <img src="{{ asset('storage/' . $experience->cover_path) }}" alt="Logo {{ $experience->organization_name }} - {{ $experience->position }}" loading="lazy" class="experience-logo-img">
                @else
                    <div class="experience-logo-fallback d-flex align-items-center justify-content-center w-100 h-100">
                        @if(($badgeType ?? '') === 'organisasi' || $experience->type?->value === 'organization')
                            <i class="bi bi-diagram-2-fill text-warning fs-3"></i>
                        @else
                            <i class="bi bi-briefcase-fill text-primary fs-3"></i>
                        @endif
                    </div>
                @endif
            </div>
            <div>
                @if(($badgeType ?? '') === 'organisasi' || $experience->type?->value === 'organization')
                    <span class="sticker sticker-yellow mb-2">
                        <i class="bi bi-diagram-2-fill"></i> ORGANISASI
                    </span>
                @else
                    <span class="sticker sticker-blue mb-2">
                        <i class="bi bi-briefcase-fill"></i> MAGANG
                    </span>
                @endif
                <h3 class="font-heading mb-1">{{ $experience->position }}</h3>
                <h5 class="text-primary fw-bold mb-0">{{ $experience->organization_name }}</h5>
            </div>
        </div>
        <div class="text-md-end flex-shrink-0">
            <span class="badge bg-light text-dark border border-2 border-dark rounded-pill px-3 py-2 fw-bold">
                {{ $experience->start_date?->format('M Y') }} — {{ $experience->is_current ? 'Sekarang' : ($experience->end_date ? $experience->end_date->format('M Y') : '-') }}
            </span>
            @if($experience->location)
                <div class="small text-muted mt-1"><i class="bi bi-geo-alt"></i> {{ $experience->location }}</div>
            @endif
        </div>
    </div>

    @if($experience->summary)
        <p class="text-muted fs-6 mb-3">
            {{ $experience->summary }}
        </p>
    @endif

    @if($experience->highlights->count())
        <div class="p-3 card-genz-blue rounded-4 mb-4">
            <h6 class="fw-bold mb-2 font-heading">
                @if(($badgeType ?? '') === 'organisasi' || $experience->type?->value === 'organization')
                    <i class="bi bi-trophy-fill text-warning"></i> Sorotan Tanggung Jawab:
                @else
                    <i class="bi bi-stars text-warning"></i> Sorotan Pencapaian Utama:
                @endif
            </h6>
            <ul class="mb-0 ps-3">
                @foreach($experience->highlights as $highlight)
                    <li class="mb-1">{{ $highlight->content }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top border-2 border-light mt-auto">
        <div class="d-flex flex-wrap gap-1">
            @foreach($experience->technologies as $tech)
                <span class="pill-tag">{{ $tech->name }}</span>
            @endforeach
        </div>

        <div class="d-flex flex-wrap gap-2">
            @foreach($experience->attachments as $att)
                @if($att->isFile() && $att->file_path)
                    <a href="{{ asset('storage/' . $att->file_path) }}" download class="btn-genz btn-genz-yellow py-1 px-3 fs-6">
                        <i class="bi bi-download"></i> {{ $att->title }}
                    </a>
                @elseif($att->isLink() && $att->url)
                    <a href="{{ $att->url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary py-1 px-3 fs-6">
                        <i class="bi bi-box-arrow-up-right"></i> {{ $att->title }}
                    </a>
                @endif
            @endforeach
            <a href="{{ route('portfolio.experience.show', $experience->id) }}" class="btn-genz btn-genz-primary py-1 px-3 fs-6">
                Cerita Lengkap <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
