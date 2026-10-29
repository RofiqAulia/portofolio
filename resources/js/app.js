import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Gen-Z Carousel: Indicator Sync & Mouse Drag Support
    document.querySelectorAll('.carousel-genz').forEach(carouselEl => {
        carouselEl.addEventListener('slid.bs.carousel', (e) => {
            const container = carouselEl.closest('.carousel-container') || carouselEl.closest('.card-genz') || carouselEl;
            const indicators = container.querySelectorAll('.carousel-indicator-pill');
            indicators.forEach((ind, idx) => {
                if (idx === e.to) {
                    ind.classList.add('active');
                } else {
                    ind.classList.remove('active');
                }
            });
            const counter = container.querySelector('.carousel-counter-current');
            if (counter) {
                counter.textContent = e.to + 1;
            }
        });

        // Mouse Drag / Swipe
        let startX = 0;
        let isDragging = false;

        carouselEl.addEventListener('mousedown', (e) => {
            if (e.target.closest('a, button, input, textarea, select')) {
                return;
            }
            startX = e.clientX;
            isDragging = true;
        });

        const stopDragging = (e) => {
            if (!isDragging) return;
            isDragging = false;
            const diffX = startX - e.clientX;
            const threshold = 40;

            if (Math.abs(diffX) > threshold) {
                const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                if (diffX > threshold) {
                    carouselInstance.next();
                } else {
                    carouselInstance.prev();
                }
            }
        };

        carouselEl.addEventListener('mouseup', stopDragging);
        carouselEl.addEventListener('mouseleave', () => {
            isDragging = false;
        });
    });

    // Responsive 2-Container Slider: (Ala Gambar 2: Max 2 Container pada Desktop, 1 pada Mobile)
    const initTwoColSlider = (track) => {
        if (!track) return;

        const targetId = '#' + track.id;
        const prevBtns = document.querySelectorAll(`[data-target="${targetId}"].btn-slider-prev, [data-target="${targetId}"].project-nav-prev, .project-btn-prev`);
        const nextBtns = document.querySelectorAll(`[data-target="${targetId}"].btn-slider-next, [data-target="${targetId}"].project-nav-next, .project-btn-next`);
        const items = track.querySelectorAll('.slider-item-2col, .slider-item-3col, .project-slide-item, .internship-slide-item, .organization-slide-item, .certification-slide-item');
        const totalItems = items.length;

        if (totalItems === 0) return;

        const getStep = () => {
            const firstItem = items[0];
            if (!firstItem) return 360;
            const style = window.getComputedStyle(track);
            const gap = parseFloat(style.gap) || 24;
            return firstItem.offsetWidth + gap;
        };

        const updateControls = () => {
            const maxScroll = track.scrollWidth - track.clientWidth;
            const hasOverflow = maxScroll > 8;

            prevBtns.forEach(btn => {
                btn.style.opacity = hasOverflow ? '1' : '0.4';
                btn.style.pointerEvents = hasOverflow ? 'auto' : 'none';
            });
            nextBtns.forEach(btn => {
                btn.style.opacity = hasOverflow ? '1' : '0.4';
                btn.style.pointerEvents = hasOverflow ? 'auto' : 'none';
            });
        };

        // Prev buttons
        prevBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const step = getStep();
                if (track.scrollLeft <= 15) {
                    track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
                } else {
                    track.scrollBy({ left: -step, behavior: 'smooth' });
                }
            });
        });

        // Next buttons
        nextBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const step = getStep();
                const maxScroll = track.scrollWidth - track.clientWidth;
                if (track.scrollLeft >= maxScroll - 15) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    track.scrollBy({ left: step, behavior: 'smooth' });
                }
            });
        });

        // Scroll listener
        let ticking = false;
        track.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    updateControls();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        window.addEventListener('resize', () => {
            updateControls();
        });

        // Desktop mouse drag
        let isDown = false;
        let startX = 0;
        let initialScrollLeft = 0;
        let hasMoved = false;

        track.addEventListener('mousedown', (e) => {
            if (e.target.closest('a, button, input, textarea, select')) {
                return;
            }
            isDown = true;
            hasMoved = false;
            track.classList.add('is-dragging');
            startX = e.pageX - track.offsetLeft;
            initialScrollLeft = track.scrollLeft;
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.25;
            if (Math.abs(walk) > 6) {
                hasMoved = true;
            }
            track.scrollLeft = initialScrollLeft - walk;
        });

        const stopDragging = () => {
            if (!isDown) return;
            isDown = false;
            track.classList.remove('is-dragging');
        };

        window.addEventListener('mouseup', stopDragging);

        track.addEventListener('click', (e) => {
            if (hasMoved) {
                e.preventDefault();
                e.stopPropagation();
                hasMoved = false;
            }
        }, true);

        // Initial setup deferred to prevent forced synchronous reflow during initial page parse
        if ('requestAnimationFrame' in window) {
            window.requestAnimationFrame(() => updateControls());
        } else {
            setTimeout(updateControls, 50);
        }
    };

    // Initialize all sliders (2-container & 3-container tracks)
    document.querySelectorAll('.slider-track-2col, .slider-track-3col, #projectSliderTrack, #internshipSliderTrack, #organizationSliderTrack, #experienceSliderTrack, #certificationSliderTrack').forEach(track => {
        initTwoColSlider(track);
    });

    // Bento Experience Filter (Magang & Organisasi)
    const filterButtons = document.querySelectorAll('.bento-filter-btn');
    const expTrack = document.getElementById('experienceSliderTrack');
    if (filterButtons.length && expTrack) {
        const applyFilter = (filter) => {
            filterButtons.forEach(btn => {
                if (btn.dataset.filter === filter) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            const items = expTrack.querySelectorAll('.experience-slide-item');
            items.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });

            expTrack.scrollTo({ left: 0, behavior: 'smooth' });
        };

        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                applyFilter(btn.dataset.filter);
            });
        });

        // Hash Navigation on Load or Click
        const checkHash = () => {
            const hash = window.location.hash;
            if (hash === '#magang') {
                applyFilter('magang');
            } else if (hash === '#organisasi') {
                applyFilter('organisasi');
            }
        };

        checkHash();
        window.addEventListener('hashchange', checkHash);
    }

    // Image Viewer / Lightbox & Download
    const initImageViewer = () => {
        const modalEl = document.getElementById('imageViewerModal');
        if (!modalEl) return;

        const modalTitle = document.getElementById('imageViewerTitle');
        const modalImg = document.getElementById('imageViewerImg');
        const modalDesc = document.getElementById('imageViewerDesc');
        const modalSpinner = document.getElementById('imageViewerSpinner');
        const modalOpenNewTab = document.getElementById('imageViewerOpenNewTab');
        const modalDownloadBtn = document.getElementById('imageViewerDownloadBtn');

        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);

        const openViewer = (src, title, desc, downloadUrl, filename) => {
            if (!src) return;

            if (modalSpinner) modalSpinner.style.display = 'inline-block';
            modalImg.style.opacity = '0';

            modalImg.onload = () => {
                if (modalSpinner) modalSpinner.style.display = 'none';
                modalImg.style.opacity = '1';
            };

            modalImg.onerror = () => {
                if (modalSpinner) modalSpinner.style.display = 'none';
                modalImg.style.opacity = '1';
            };

            modalImg.src = src;
            modalImg.alt = title || 'Preview Gambar';
            if (modalTitle) modalTitle.textContent = title || 'Preview Gambar';

            if (modalDesc) {
                if (desc && desc.trim()) {
                    modalDesc.textContent = desc;
                    modalDesc.style.display = 'block';
                } else {
                    modalDesc.textContent = '';
                    modalDesc.style.display = 'none';
                }
            }

            if (modalOpenNewTab) {
                modalOpenNewTab.href = src;
            }

            const finalDownloadUrl = downloadUrl || src;
            if (modalDownloadBtn) {
                modalDownloadBtn.href = finalDownloadUrl;
                if (filename) {
                    modalDownloadBtn.setAttribute('download', filename);
                } else {
                    modalDownloadBtn.removeAttribute('download');
                }
            }

            modalInstance.show();
        };

        // Open modal on click of any lightbox trigger
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-lightbox-trigger], .btn-lightbox-open');
            if (trigger) {
                e.preventDefault();
                e.stopPropagation();

                const src = trigger.getAttribute('data-image-src');
                const title = trigger.getAttribute('data-image-title');
                const desc = trigger.getAttribute('data-image-desc');
                const downloadUrl = trigger.getAttribute('data-image-download');
                const filename = trigger.getAttribute('data-image-filename');

                openViewer(src, title, desc, downloadUrl, filename);
            }
        });

        // Keyboard accessibility: Enter or Space on [data-lightbox-trigger]
        document.addEventListener('keydown', (e) => {
            if ((e.key === 'Enter' || e.key === ' ') && e.target && e.target.matches && e.target.matches('[data-lightbox-trigger]')) {
                e.preventDefault();
                e.target.click();
            }
        });
    };

    initImageViewer();

    // ==========================================
    // Dynamic Skill Pyramid Diagram Hover Synchronization
    // ==========================================
    const initSkillPyramid = () => {
        document.querySelectorAll('[data-target-tier]').forEach(el => {
            const targetId = el.getAttribute('data-target-tier');
            if (!targetId) return;

            el.addEventListener('mouseenter', () => {
                document.querySelectorAll(`[data-target-tier="${targetId}"]`).forEach(sibling => {
                    sibling.classList.add('is-active');
                });
            });

            el.addEventListener('mouseleave', () => {
                document.querySelectorAll(`[data-target-tier="${targetId}"]`).forEach(sibling => {
                    sibling.classList.remove('is-active');
                });
            });
        });
    };

    initSkillPyramid();
});
