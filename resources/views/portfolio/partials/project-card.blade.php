<div class="project-bento-card h-100">
    <div>
        {{-- 1. Sharp, Prominent Highlight Image Stage (Full Width) --}}
        <div class="project-preview-stage">
            <!-- Browser Chrome Header Bar -->
            <div class="project-preview-bar">
                <div class="inspo-mockup-dots d-flex align-items-center gap-1">
                    <span class="dot dot-red"></span>
                    <span class="dot dot-yellow"></span>
                    <span class="dot dot-green"></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-white text-dark border rounded-pill px-2 py-0 small font-monospace" style="font-size: 0.7rem;">
                        {{ Str::slug($project->title) }}.app
                    </span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="status-pulse-dot" style="width: 6px; height: 6px;"></span>
                    <span class="text-muted small fw-semibold" style="font-size: 0.68rem;">Live</span>
                </div>
            </div>

            <!-- Screen Area with Sharp Image -->
            <div class="project-preview-screen position-relative">
                @if($project->thumbnail_path)
                    <img src="{{ asset('storage/' . $project->thumbnail_path) }}" 
                         alt="{{ $project->title }}" 
                         loading="lazy" 
                         decoding="async"
                         width="600"
                         height="340"
                         class="project-preview-img"
                         role="button"
                         tabindex="0"
                         aria-label="Lihat gambar {{ $project->title }}"
                         data-lightbox-trigger
                         data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                         data-image-title="{{ $project->title }}"
                         data-image-desc="{{ $project->summary }}"
                         data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                         data-image-filename="{{ Str::slug($project->title) }}-preview">

                    <!-- Floating Badges on Screen -->
                    <div class="position-absolute top-0 start-0 m-2 d-flex flex-wrap gap-1 z-2">
                        <span class="bento-badge-pill bento-badge-blue py-1 px-3 shadow-sm">
                            <i class="bi bi-code-slash me-1"></i> {{ $project->role ?? 'Software Engineer' }}
                        </span>
                    </div>

                    @if($project->is_featured)
                        <div class="position-absolute top-0 end-0 m-2 z-2">
                            <span class="bento-badge-pill bento-badge-amber py-1 px-3 shadow-sm">
                                <i class="bi bi-star-fill text-warning me-1"></i> UNGGULAN
                            </span>
                        </div>
                    @endif

                    <!-- Hover Quick Actions Overlay -->
                    <div class="project-preview-overlay">
                        <button type="button" 
                                class="btn-bento-primary py-2 px-3 fs-6 btn-lightbox-open shadow"
                                data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                data-image-title="{{ $project->title }}"
                                data-image-desc="{{ $project->summary }}"
                                data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                                data-image-filename="{{ Str::slug($project->title) }}-preview"
                                title="Perbesar Gambar Resolusi Penuh">
                            <i class="bi bi-arrows-angle-expand me-1"></i> Preview Penuh
                        </button>
                        <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" 
                           class="btn-bento-amber py-2 px-3 fs-6 shadow"
                           title="Unduh Gambar Resolusi Tinggi">
                            <i class="bi bi-download me-1"></i> Unduh
                        </a>
                    </div>
                @else
                    {{-- Clean Branded Graphic Fallback --}}
                    <div class="inspo-graphic-fallback w-100 h-100 d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden">
                        <div class="inspo-graphic-icon-wrap mb-2">
                            <i class="bi bi-laptop fs-2 text-primary"></i>
                        </div>
                        <span class="badge bg-white text-dark rounded-pill px-3 py-1 shadow-sm small fw-bold mb-1">
                            {{ $project->role ?? 'Software Project' }}
                        </span>
                        <span class="text-muted small" style="font-size: 0.75rem;">Production Ready Web App</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Metadata, Title & Summary --}}
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <span class="small text-muted fw-semibold">
                <i class="bi bi-calendar-check me-1 text-primary"></i>
                {{ $project->finished_at ? $project->finished_at->format('M Y') : ($project->started_at ? $project->started_at->format('M Y') : 'Siap Produksi') }}
            </span>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small fw-bold">
                <i class="bi bi-check-circle-fill me-1"></i> {{ $project->status?->label() ?? 'Selesai' }}
            </span>
        </div>

        <h3 class="project-card-title mb-2">
            <a href="{{ route('portfolio.project.show', $project->slug) }}">
                {{ $project->title }}
            </a>
        </h3>

        <p class="inspo-card-desc mb-3 text-dark">
            {{ $project->summary }}
        </p>

        <!-- Tech Stack Tags -->
        @if($project->technologies && $project->technologies->isNotEmpty())
            <div class="inspo-tags-row mb-3">
                @foreach($project->technologies->take(4) as $tech)
                    <span class="bento-badge-pill bento-badge-blue py-0 px-2 small">{{ $tech->name }}</span>
                @endforeach
                @if($project->technologies->count() > 4)
                    <span class="badge bg-light text-muted border rounded-pill px-2 py-0 small">+{{ $project->technologies->count() - 4 }}</span>
                @endif
            </div>
        @endif
    </div>

    {{-- 3. Card Footer Action Bar with Transparent BG-Image Overlay --}}
    <div class="project-action-overlay position-relative mt-auto overflow-hidden rounded-4">
        {{-- Background image at 20% opacity --}}
        @if($project->thumbnail_path)
            <div class="project-action-bg" style="background-image: url('{{ asset('storage/' . $project->thumbnail_path) }}');"></div>
        @else
            <div class="project-action-bg project-action-bg-fallback"></div>
        @endif

        {{-- Overlay content --}}
        <div class="project-action-content d-flex align-items-center justify-content-between gap-2 position-relative z-2">
            <a href="{{ route('portfolio.project.show', $project->slug) }}" class="btn-bento-primary py-2 px-3 fs-6">
                <span>Detail Cerita</span>
                <i class="bi bi-arrow-right"></i>
            </a>

            <div class="inspo-action-icons">
                @if($project->demo_url)
                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn-inspo-icon" title="Buka Live Demo">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                @endif
                @if($project->repo_url)
                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn-inspo-icon" title="Lihat Source Code di GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                @endif
                @if($project->thumbnail_path)
                    <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" class="btn-inspo-icon" title="Unduh Tangkapan Layar">
                        <i class="bi bi-download"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
