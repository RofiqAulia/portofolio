@props(['certification'])

<div class="modal fade" id="certModal-{{ $certification->id }}" tabindex="-1" aria-labelledby="certModalLabel-{{ $certification->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Header --}}
            <div class="modal-header border-bottom bg-light px-4 py-3">
                <div class="pe-3">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge {{ $certification->getCategoryBadgeClass() }} rounded-pill px-3 py-1 fw-semibold small">
                            <i class="bi {{ $certification->getCategoryIcon() }} me-1"></i> {{ $certification->category }}
                        </span>
                        @if($certification->issued_year)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 small">
                                {{ $certification->issued_year }}
                            </span>
                        @endif
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1 small">
                            <i class="bi bi-patch-check-fill text-primary me-1"></i> {{ $certification->status_label ?: 'Terverifikasi' }}
                        </span>
                    </div>
                    <h5 class="modal-title font-heading fs-5 text-dark mb-0" id="certModalLabel-{{ $certification->id }}">
                        {{ $certification->title }}
                    </h5>
                    @if($certification->issuer)
                        <div class="small text-muted mt-1">
                            <i class="bi bi-buildings me-1"></i> Penerbit: <strong>{{ $certification->issuer }}</strong>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-close align-self-start" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4">
                {{-- 1. Gambar / Foto Multi-Upload (Carousel atau Single Display) --}}
                @if($certification->hasImages())
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-images text-primary me-1"></i> Foto / Scan Sertifikat ({{ $certification->getImageCount() }})
                            </h6>
                            <span class="text-muted small">Klik gambar untuk pratinjau penuh</span>
                        </div>

                        @if($certification->getImageCount() === 1)
                            @php
                                $singleImg = $certification->getImageItems()[0];
                            @endphp
                            <div class="position-relative rounded-3 overflow-hidden border bg-light text-center p-2">
                                <img src="{{ $singleImg['url'] }}" 
                                     alt="{{ $certification->title }}" 
                                     class="img-fluid rounded-2" 
                                     style="max-height: 420px; width: auto; object-fit: contain;">
                                <div class="mt-2 text-end">
                                    <a href="{{ route('portfolio.certifications.download-image', ['slug' => $certification->slug, 'index' => 0]) }}" 
                                       class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="bi bi-download me-1"></i> Unduh Gambar
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- Carousel for Multiple Images --}}
                            <div id="certCarousel-{{ $certification->id }}" class="carousel slide border rounded-3 overflow-hidden bg-light" data-bs-ride="false">
                                <div class="carousel-indicators">
                                    @foreach($certification->getImageItems() as $idx => $imgItem)
                                        <button type="button" 
                                                data-bs-target="#certCarousel-{{ $certification->id }}" 
                                                data-bs-slide-to="{{ $idx }}" 
                                                class="{{ $idx === 0 ? 'active' : '' }}" 
                                                aria-current="{{ $idx === 0 ? 'true' : 'false' }}" 
                                                aria-label="Slide {{ $idx + 1 }}"></button>
                                    @endforeach
                                </div>

                                <div class="carousel-inner">
                                    @foreach($certification->getImageItems() as $idx => $imgItem)
                                        <div class="carousel-item {{ $idx === 0 ? 'active' : '' }} text-center p-3" style="min-height: 320px; max-height: 440px;">
                                            <img src="{{ $imgItem['url'] }}" 
                                                 alt="{{ $certification->title }} slide {{ $idx + 1 }}" 
                                                 class="img-fluid rounded-2 mx-auto d-block" 
                                                 style="max-height: 380px; width: auto; object-fit: contain;">
                                            <div class="mt-2 text-center">
                                                <a href="{{ route('portfolio.certifications.download-image', ['slug' => $certification->slug, 'index' => $idx]) }}" 
                                                   class="btn btn-sm btn-outline-dark bg-white rounded-pill shadow-sm">
                                                    <i class="bi bi-download me-1"></i> Unduh Foto {{ $idx + 1 }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#certCarousel-{{ $certification->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark bg-opacity-50 rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="visually-hidden">Sebelumnya</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#certCarousel-{{ $certification->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark bg-opacity-50 rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="visually-hidden">Selanjutnya</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- 2. Deskripsi Capaian --}}
                @if($certification->description)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2 text-dark">
                            <i class="bi bi-info-circle text-primary me-1"></i> Keterangan Capaian
                        </h6>
                        <div class="p-3 bg-light rounded-3 text-secondary" style="line-height: 1.65; font-size: 0.95rem;">
                            {{ $certification->description }}
                        </div>
                    </div>
                @endif

                {{-- 3. Berkas / Dokumen Resmi Multi-Upload (PDF, DOCX, ZIP) --}}
                @if($certification->hasFiles())
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 text-dark">
                            <i class="bi bi-folder-symlink-fill text-danger me-1"></i> Berkas &amp; Dokumen Resmi ({{ $certification->getFileCount() }})
                        </h6>
                        <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                            @foreach($certification->getFileItems() as $fIdx => $fItem)
                                <div class="list-group-item d-flex align-items-center justify-content-between p-3 gap-2 flex-wrap">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 rounded bg-danger bg-opacity-10 text-danger fs-4 flex-shrink-0">
                                            @if($fItem['extension'] === 'pdf')
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            @elseif(in_array($fItem['extension'], ['doc', 'docx']))
                                                <i class="bi bi-file-earmark-word-fill text-primary"></i>
                                            @elseif(in_array($fItem['extension'], ['zip', 'rar']))
                                                <i class="bi bi-file-earmark-zip-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-file-earmark-text-fill"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark text-break">{{ $fItem['name'] }}</div>
                                            <div class="small text-muted">
                                                <span class="badge bg-light text-dark border me-1 text-uppercase">{{ $fItem['extension'] }}</span>
                                                @if($fItem['size'])
                                                    <span>{{ $fItem['size'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('portfolio.certifications.download-file', ['slug' => $certification->slug, 'index' => $fIdx]) }}" 
                                           class="btn btn-sm btn-primary rounded-pill px-3 py-1">
                                            <i class="bi bi-download me-1"></i> Unduh Berkas
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 4. Kredensial Online / Eksternal Link --}}
                @if($certification->credential_url || $certification->credential_id)
                    <div class="p-3 bg-light bg-opacity-75 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 border">
                        <div>
                            @if($certification->credential_id)
                                <div class="small text-muted">Nomor Registrasi / Kredensial:</div>
                                <div class="font-monospace fw-bold text-dark">{{ $certification->credential_id }}</div>
                            @endif
                        </div>

                        @if($certification->credential_url)
                            <a href="{{ $certification->credential_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                Verifikasi Resmi <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top bg-light px-4 py-2 d-flex justify-content-between">
                <span class="small text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i> Arsip Terverifikasi Portofolio
                </span>
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
