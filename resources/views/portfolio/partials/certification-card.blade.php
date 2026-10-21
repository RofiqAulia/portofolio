@props(['certification'])

<div class="inspo-card h-100 p-3 p-sm-4 bg-white d-flex flex-column w-100 position-relative">
    {{-- 1. Header: Logo Circle Stage + Category Badge & Year --}}
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
        <div class="d-flex align-items-center gap-3 min-w-0">
            {{-- Circular Logo / Crest Stage --}}
            <div class="cert-logo-circle flex-shrink-0" style="background-color: {{ $certification->getCategoryBadgeStyle()['bg'] }}; border-color: {{ $certification->getCategoryBadgeStyle()['border'] }};">
                @if($certification->getLogoUrl())
                    <img src="{{ $certification->getLogoUrl() }}" 
                         alt="{{ $certification->issuer ?: $certification->title }}" 
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy">
                @else
                    <i class="bi {{ $certification->getCategoryIcon() }}" style="color: {{ $certification->getCategoryBadgeStyle()['text'] }};"></i>
                @endif
            </div>

            <div class="min-w-0">
                {{-- Category Badge (Guaranteed 100% High Contrast & Visible) --}}
                <span class="{{ $certification->getCategoryBadgeClass() }} mb-1 d-inline-flex align-items-center">
                    <i class="bi {{ $certification->getCategoryIcon() }}"></i> {{ $certification->category }}
                </span>
                @if($certification->issuer)
                    <div class="text-muted small fw-medium text-truncate" style="font-size: 0.8rem;" title="{{ $certification->issuer }}">
                        <i class="bi bi-buildings me-1 opacity-75"></i> {{ $certification->issuer }}
                    </div>
                @endif
            </div>
        </div>

        @if($certification->issued_year)
            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small fw-bold flex-shrink-0">
                {{ $certification->issued_year }}
            </span>
        @endif
    </div>

    {{-- Optional Preview Image / Scan Thumbnail --}}
    @if($certification->hasImages())
        <div class="position-relative mb-3 rounded-3 overflow-hidden border bg-light d-flex align-items-center justify-content-center" 
             style="height: 140px; cursor: pointer;"
             data-bs-toggle="modal" 
             data-bs-target="#certModal-{{ $certification->id }}">
            <img src="{{ $certification->getFirstImageUrl() }}" 
                 alt="{{ $certification->title }}" 
                 class="w-100 h-100 object-fit-cover transition-transform" 
                 loading="lazy">
            @if($certification->getImageCount() > 1)
                <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 text-white rounded-pill px-2 py-1 small">
                    <i class="bi bi-images me-1"></i> {{ $certification->getImageCount() }} Foto
                </span>
            @endif
        </div>
    @endif

    {{-- 2. Title & Credential --}}
    <h5 class="font-heading fs-5 mb-2 text-dark">{{ $certification->title }}</h5>

    @if($certification->credential_id)
        <div class="small text-muted mb-2 font-monospace" style="font-size: 0.8rem;">
            <i class="bi bi-upc-scan me-1"></i> {{ $certification->credential_id }}
        </div>
    @endif

    {{-- 3. Description --}}
    <p class="small mb-3 flex-grow-1" style="color: #000000 !important; line-height: 1.6;">
        {{ $certification->description }}
    </p>

    {{-- 4. Media badges indicator (if any files or photos) --}}
    @if($certification->hasFiles() || $certification->hasImages())
        <div class="d-flex flex-wrap gap-1 mb-3 pt-2 border-top border-light">
            @if($certification->hasImages())
                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                    <i class="bi bi-image-fill text-primary me-1"></i> {{ $certification->getImageCount() }} Foto
                </span>
            @endif
            @if($certification->hasFiles())
                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                    <i class="bi bi-file-earmark-arrow-down-fill text-danger me-1"></i> {{ $certification->getFileCount() }} Dokumen
                </span>
            @endif
        </div>
    @endif

    {{-- 5. Footer Actions --}}
    <div class="pt-3 mt-auto border-top d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 small">
            <i class="bi bi-patch-check-fill text-primary me-1"></i> {{ $certification->status_label ?: 'Terverifikasi' }}
        </span>

        <div class="d-flex align-items-center gap-2">
            @if($certification->hasImages() || $certification->hasFiles())
                <button type="button" 
                        class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5"
                        data-bs-toggle="modal" 
                        data-bs-target="#certModal-{{ $certification->id }}">
                    <i class="bi bi-folder2-open me-1"></i> Lihat Berkas
                </button>
            @elseif($certification->credential_url)
                <a href="{{ $certification->credential_url }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5">
                    Verifikasi <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            @elseif($certification->action_url)
                <a href="{{ $certification->action_url }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5">
                    {{ $certification->action_label ?: 'Detail' }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @endif
        </div>
    </div>
</div>
