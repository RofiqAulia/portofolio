<!-- Global Image Viewer / Lightbox Modal -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-labelledby="imageViewerTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" id="imageViewerDialog">
        <div class="modal-content border-0 rounded-4 shadow-lg p-0 overflow-hidden bg-white">
            <!-- Modal Header -->
            <div class="modal-header border-bottom bg-white px-3 px-md-4 py-3 align-items-center">
                <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden pe-2">
                    <span class="badge bg-primary text-white rounded-circle p-2 fs-6 flex-shrink-0">
                        <i class="bi bi-image"></i>
                    </span>
                    <div class="overflow-hidden">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small mb-1 d-inline-block fw-bold" style="font-size: 0.72rem;">
                            PREVIEW GAMBAR
                        </span>
                        <h4 class="modal-title font-heading mb-0 text-truncate fs-5 fs-md-4 text-dark" id="imageViewerTitle">
                            Preview Gambar
                        </h4>
                    </div>
                </div>
                <button type="button" class="btn-inspo-icon flex-shrink-0" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg fs-6"></i>
                </button>
            </div>

            <!-- Modal Body (Edge-to-Edge Full-Width Image Stage) -->
            <div class="modal-body p-0 position-relative image-viewer-body bg-light">
                <div class="image-viewer-stage position-relative overflow-hidden w-100 d-flex align-items-center justify-content-center">
                    <div id="imageViewerSpinner" class="spinner-border text-primary position-absolute" role="status" style="display: none; z-index: 10;">
                        <span class="visually-hidden">Memuat gambar...</span>
                    </div>
                    <img src="" id="imageViewerImg" alt="Preview Gambar" class="w-100 image-viewer-photo d-block">
                </div>
                
                <div id="imageViewerDescWrap" class="px-3 px-md-4 py-3 bg-white border-top text-start" style="display: none;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-primary flex-shrink-0"></i>
                        <p id="imageViewerDesc" class="text-dark small mb-0 fw-medium"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-white px-3 px-md-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="text-muted small d-none d-md-block">
                    <i class="bi bi-shield-check text-success me-1"></i> File gambar terverifikasi asli resolusi penuh.
                </div>
                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-end">
                    <a href="#" id="imageViewerOpenNewTab" target="_blank" rel="noopener noreferrer" class="btn-inspo-outline py-2 px-3 fs-6">
                        <i class="bi bi-box-arrow-up-right"></i> Buka Asli
                    </a>
                    <a href="#" id="imageViewerDownloadBtn" class="btn-inspo-header py-2 px-3 fs-6">
                        <i class="bi bi-download"></i> Unduh Gambar
                    </a>
                    <button type="button" class="btn btn-light rounded-pill px-3 py-2 fw-semibold text-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
