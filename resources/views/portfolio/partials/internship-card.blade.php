<div class="bento-card bento-card-amber bento-experience-card h-100">
    <div class="bento-card-content position-relative z-2 d-flex flex-column h-100">
        {{-- 1. Prominent BULAT (Circular) Logo Stage --}}
        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bento-logo-circle flex-shrink-0" itemscope itemtype="https://schema.org/Organization">
                    @if($experience->cover_path)
                        <img src="{{ asset('storage/' . $experience->cover_path) }}" 
                             itemprop="logo"
                             alt="Logo Mitra {{ $experience->organization_name }}" 
                             class="bento-logo-img-circle"
                             width="56"
                             height="56"
                             loading="lazy"
                             decoding="async">
                        <meta itemprop="name" content="{{ $experience->organization_name }}">
                    @else
                        <i class="bi bi-buildings-fill text-warning fs-3"></i>
                    @endif
                </div>
                <div>
                    <span class="bento-badge mb-1 d-inline-flex align-items-center">
                        <i class="bi bi-buildings-fill me-1"></i> INDUSTRI RIIL
                    </span>
                    <div class="text-white small fw-bold" style="font-size: 0.8rem;">
                        <i class="bi bi-patch-check-fill text-warning me-1"></i> Mitra Terverifikasi
                    </div>
                </div>
            </div>

            <div class="d-none d-sm-block text-end">
                <span class="bento-date-pill">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ $experience->start_date?->format('M Y') }} — {{ $experience->is_current ? 'Sekarang' : ($experience->end_date ? $experience->end_date->format('M Y') : '-') }}
                </span>
            </div>
        </div>

        {{-- Mobile Date Badge --}}
        <div class="d-sm-none mb-3">
            <span class="bento-date-pill">
                <i class="bi bi-calendar-event me-1"></i>
                {{ $experience->start_date?->format('M Y') }} — {{ $experience->is_current ? 'Sekarang' : ($experience->end_date ? $experience->end_date->format('M Y') : '-') }}
            </span>
        </div>

        {{-- 2. Organization Name, Position, and Location --}}
        <h3 class="bento-card-heading mb-1 text-white">
            <a href="{{ route('portfolio.experience.show', $experience->slug ?? $experience->id) }}" class="text-white text-decoration-none">
                {{ $experience->organization_name }}
            </a>
        </h3>

        <div class="text-white text-opacity-90 fw-bold fs-6 mb-2 text-truncate">
            <i class="bi bi-briefcase-fill me-1 text-white text-opacity-75"></i> {{ $experience->position }}
            @if($experience->location)
                <span class="small fw-normal text-white text-opacity-80 ms-1">• <i class="bi bi-geo-alt-fill text-white text-opacity-75"></i> {{ $experience->location }}</span>
            @endif
        </div>

        <!-- Summary (Equal Baseline & Clamped) -->
        <p class="bento-desc mb-3 text-white text-opacity-95" style="font-size: 0.92rem;">
            {{ $experience->summary ?: 'Menjalankan program magang dengan fokus pada efisiensi kerja, analisis proses bisnis, dan implementasi continuous improvement.' }}
        </p>

        <!-- Tech Tags -->
        <div class="bento-tags-row mb-3">
            @if($experience->technologies && $experience->technologies->isNotEmpty())
                @foreach($experience->technologies->take(4) as $tech)
                    <span class="bento-tag-pill">{{ $tech->name }}</span>
                @endforeach
                @if($experience->technologies->count() > 4)
                    <span class="bento-tag-pill">+{{ $experience->technologies->count() - 4 }}</span>
                @endif
            @else
                <span class="bento-tag-pill"><i class="bi bi-stars me-1"></i> Continuous Improvement</span>
                <span class="bento-tag-pill"><i class="bi bi-check2-circle me-1"></i> Data Analytics</span>
            @endif
        </div>

        {{-- 3. Action Footer --}}
        <div class="d-flex align-items-center justify-content-between gap-2 mt-auto pt-3 border-top border-white border-opacity-20">
            <a href="{{ route('portfolio.experience.show', $experience->slug ?? $experience->id) }}" 
               class="btn-bento-pill text-amber"
               aria-label="Telusuri Jejak Magang di {{ $experience->organization_name ?? $experience->company ?? 'Industri' }}">
                <span>Telusuri Jejak Magang</span>
                <i class="bi bi-arrow-right"></i>
            </a>

            @if($experience->attachments && $experience->attachments->isNotEmpty())
                <div class="d-flex align-items-center gap-1">
                    @foreach($experience->attachments->take(2) as $att)
                        @if($att->isFile() && $att->file_path)
                            <a href="{{ asset('storage/' . $att->file_path) }}" download class="btn-bento-icon-white" title="Unduh {{ $att->title }}">
                                <i class="bi bi-download"></i>
                            </a>
                        @elseif($att->isLink() && $att->url)
                            <a href="{{ $att->url }}" target="_blank" rel="noopener noreferrer" class="btn-bento-icon-white" title="Buka {{ $att->title }}">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Visual Watermark Decoration -->
    <div class="bento-visual bento-visual-building" aria-hidden="true">
        <i class="bi bi-buildings-fill"></i>
    </div>
</div>
