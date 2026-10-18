<div class="card-genz h-100 d-flex flex-column bg-white overflow-hidden">
    @if($project->thumbnail_path)
        <div class="project-thumbnail-box position-relative overflow-hidden border-bottom border-2 border-dark" style="height: 240px;">
            <img src="{{ asset('storage/' . $project->thumbnail_path) }}" 
                 alt="{{ $project->title }}" 
                 class="w-100 h-100 object-fit-cover project-thumbnail-img"
                 role="button"
                 tabindex="0"
                 aria-label="Lihat gambar {{ $project->title }}"
                 data-lightbox-trigger
                 data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                 data-image-title="{{ $project->title }}"
                 data-image-desc="{{ $project->summary }}"
                 data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                 data-image-filename="{{ Str::slug($project->title) }}-preview">

            <!-- Hover overlay with View and Download buttons (Desktop) -->
            <div class="project-thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                <button type="button" 
                        class="btn-genz btn-genz-primary py-1 px-3 fs-6 btn-lightbox-open"
                        data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                        data-image-title="{{ $project->title }}"
                        data-image-desc="{{ $project->summary }}"
                        data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                        data-image-filename="{{ Str::slug($project->title) }}-preview"
                        title="Lihat Gambar">
                    <i class="bi bi-eye-fill"></i> View
                </button>
                <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" 
                   class="btn-genz btn-genz-yellow py-1 px-3 fs-6"
                   title="Unduh Gambar Asli">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>

            <!-- Image indicator badge (Top-Left) -->
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2 py-1 small d-inline-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-image"></i>
                </span>
            </div>

            <!-- Mobile quick action icons (Bottom-Right) -->
            <div class="position-absolute bottom-0 end-0 m-2 d-md-none">
                <div class="d-flex gap-1">
                    <button type="button" 
                            class="btn-genz btn-genz-primary p-0 rounded-circle btn-lightbox-open"
                            style="width: 36px; height: 36px; min-height: 36px;"
                            data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                            data-image-title="{{ $project->title }}"
                            data-image-desc="{{ $project->summary }}"
                            data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                            data-image-filename="{{ Str::slug($project->title) }}-preview"
                            aria-label="Lihat Gambar"
                            title="Lihat Gambar">
                        <i class="bi bi-eye-fill small"></i>
                    </button>
                    <a href="{{ route('portfolio.project.download-thumbnail', $project->slug) }}" 
                       class="btn-genz btn-genz-yellow p-0 rounded-circle"
                       style="width: 36px; height: 36px; min-height: 36px;"
                       aria-label="Download Gambar"
                       title="Download Gambar">
                        <i class="bi bi-download small"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="w-100 bg-light d-flex align-items-center justify-content-center border-bottom border-2 border-dark" style="height: 240px;">
            <i class="bi bi-folder-fill display-2 text-muted"></i>
        </div>
    @endif

    <div class="p-4 d-flex flex-column flex-grow-1">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            @if($project->is_featured)
                <span class="sticker sticker-yellow py-1 px-2" style="font-size: 0.75rem;">
                    ★ FEATURED
                </span>
            @else
                <span></span>
            @endif
            <span class="text-muted small fw-bold">
                {{ $project->role ?? 'Software Developer' }}
            </span>
        </div>

        <h4 class="font-heading mb-2">
            <a href="{{ route('portfolio.project.show', $project->slug) }}" class="text-decoration-none text-dark">
                {{ $project->title }}
            </a>
        </h4>

        <p class="text-muted mb-4 flex-grow-1">
            {{ $project->summary }}
        </p>

        <div class="d-flex flex-wrap gap-1 mb-4">
            @foreach($project->technologies as $tech)
                <span class="pill-tag" style="font-size: 0.75rem; padding: 0.2rem 0.6rem;">
                    {{ $tech->name }}
                </span>
            @endforeach
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top border-2 border-light mt-auto">
            <a href="{{ route('portfolio.project.show', $project->slug) }}" class="btn-genz btn-genz-primary py-2 px-3 fs-6">
                Detail Cerita <i class="bi bi-arrow-right"></i>
            </a>
            @if($project->demo_url)
                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary py-2 px-3 fs-6">
                    Live Demo <i class="bi bi-box-arrow-up-right"></i>
                </a>
            @endif
            @if($project->repo_url)
                <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn-genz btn-genz-secondary p-2" title="Source Code" style="width: 44px; height: 44px;">
                    <i class="bi bi-github fs-5"></i>
                </a>
            @endif
            @if($project->thumbnail_path)
                <button type="button" 
                        class="btn-genz btn-genz-secondary p-2 btn-lightbox-open"
                        data-image-src="{{ asset('storage/' . $project->thumbnail_path) }}"
                        data-image-title="{{ $project->title }}"
                        data-image-desc="{{ $project->summary }}"
                        data-image-download="{{ route('portfolio.project.download-thumbnail', $project->slug) }}"
                        data-image-filename="{{ Str::slug($project->title) }}-preview"
                        title="Lihat & Unduh Gambar"
                        style="width: 44px; height: 44px;">
                    <i class="bi bi-image fs-5"></i>
                </button>
            @endif
        </div>
    </div>
</div>
