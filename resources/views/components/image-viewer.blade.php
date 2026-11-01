{{-- Reusable Image Viewer (Lightbox) Component with Zoom, Pan, Pinch & Gallery Navigation --}}
<div x-data="imageViewer()"
     x-show="isOpen"
     x-cloak
     @open-image-viewer.window="open($event.detail)"
     @keydown.escape.window="close()"
     @keydown.arrow-left.window="prev()"
     @keydown.arrow-right.window="next()"
     @keydown.equal.window="zoomIn()"
     @keydown.plus.window="zoomIn()"
     @keydown.minus.window="zoomOut()"
     @keydown.0.window="reset()"
     class="fixed inset-0 z-[99999] flex flex-col bg-slate-950/95 backdrop-blur-md select-none touch-none text-white font-['Plus_Jakarta_Sans',sans-serif]"
     tabindex="-1"
     aria-modal="true"
     role="dialog">

    {{-- Top Bar: Title, Counter & Action Controls --}}
    <div class="relative z-20 flex items-center justify-between px-4 py-3 sm:px-6 bg-slate-900/60 border-b border-white/10 backdrop-blur-md">
        <div class="flex items-center gap-3 min-w-0 pr-4">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="truncate">
                <h3 class="text-sm sm:text-base font-bold text-white truncate" x-text="currentItem.title || 'Pratinjau Foto'"></h3>
                <p class="text-xs text-slate-300" x-show="items.length > 1">
                    Foto <span x-text="currentIndex + 1"></span> dari <span x-text="items.length"></span>
                </p>
            </div>
        </div>

        {{-- Desktop Top Controls --}}
        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
            {{-- Zoom badge --}}
            <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200"
                  x-text="zoomPercent"></span>

            {{-- Zoom Out --}}
            <button type="button"
                    @click="zoomOut()"
                    class="p-2 rounded-lg bg-white/10 hover:bg-white/20 active:scale-95 transition text-slate-200 hover:text-white"
                    title="Perkecil (Zoom Out / -)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
            </button>

            {{-- Reset Zoom --}}
            <button type="button"
                    @click="reset()"
                    class="p-2 rounded-lg bg-white/10 hover:bg-white/20 active:scale-95 transition text-slate-200 hover:text-white"
                    title="Reset Ukuran (0)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>

            {{-- Zoom In --}}
            <button type="button"
                    @click="zoomIn()"
                    class="p-2 rounded-lg bg-white/10 hover:bg-white/20 active:scale-95 transition text-slate-200 hover:text-white"
                    title="Perbesar (Zoom In / +)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>

            {{-- Fullscreen Toggle --}}
            <button type="button"
                    @click="toggleFullscreen()"
                    class="p-2 rounded-lg bg-white/10 hover:bg-white/20 active:scale-95 transition text-slate-200 hover:text-white"
                    title="Layar Penuh (Fullscreen)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!isFullscreen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="isFullscreen" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9L4 4m0 0h4m-4 0v4m11 5l5 5m0 0h-4m4 0v-4M9 15l-5 5m0 0h4m-4 0v-4m16-7l-5-5m0 0v4m0-4h4"/>
                </svg>
            </button>

            {{-- Close --}}
            <button type="button"
                    @click="close()"
                    class="p-2 rounded-lg bg-rose-600/80 hover:bg-rose-600 active:scale-95 transition text-white ml-1"
                    title="Tutup (Esc)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Main Image Area --}}
    <div class="relative flex-1 flex items-center justify-center overflow-hidden p-2 sm:p-6"
         @click.self="close()"
         @wheel.prevent="onWheel($event)">

        {{-- Loading Spinner --}}
        <div x-show="isLoading"
             class="absolute inset-0 flex flex-col items-center justify-center gap-3 pointer-events-none z-10">
            <div class="w-12 h-12 rounded-full border-4 border-white/20 border-t-white animate-spin"></div>
            <span class="text-sm font-medium text-slate-300">Memuat gambar...</span>
        </div>

        {{-- Error State --}}
        <div x-show="hasError"
             class="flex flex-col items-center justify-center gap-3 p-6 rounded-2xl bg-slate-900/80 border border-white/10 text-center max-w-sm z-10"
             style="display: none;">
            <div class="w-12 h-12 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-white">Gambar gagal dimuat</p>
            <p class="text-xs text-slate-400">Terjadi kendala saat mengambil file foto dari server.</p>
            <button type="button"
                    @click="retry()"
                    class="mt-2 px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-xs font-semibold text-white transition active:scale-95">
                Coba Lagi
            </button>
        </div>

        {{-- Nav Button Previous --}}
        <button type="button"
                x-show="items.length > 1"
                @click.stop="prev()"
                class="absolute left-3 sm:left-6 z-20 p-3 sm:p-4 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white/80 hover:text-white border border-white/10 backdrop-blur-md active:scale-90 transition shadow-lg"
                title="Foto Sebelumnya (Arrow Left)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Nav Button Next --}}
        <button type="button"
                x-show="items.length > 1"
                @click.stop="next()"
                class="absolute right-3 sm:right-6 z-20 p-3 sm:p-4 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white/80 hover:text-white border border-white/10 backdrop-blur-md active:scale-90 transition shadow-lg"
                title="Foto Berikutnya (Arrow Right)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- The Displayed Image with Transform, Pan and Pinch Zoom --}}
        <img x-show="!hasError"
             :src="currentItem.url"
             :alt="currentItem.title"
             x-on:load="onImageLoaded()"
             x-on:error="onImageError()"
             @mousedown="startDrag($event)"
             @mousemove="onDrag($event)"
             @mouseup="stopDrag()"
             @mouseleave="stopDrag()"
             @touchstart="onTouchStart($event)"
             @touchmove="onTouchMove($event)"
             @touchend="onTouchEnd($event)"
             @dblclick="toggleZoom()"
             :style="'transform: translate3d(' + panX + 'px, ' + panY + 'px, 0px) scale(' + scale + '); cursor: ' + (scale > 1 ? (isDragging ? 'grabbing' : 'grab') : 'zoom-in') + ';'"
             class="max-h-[82vh] max-w-[92vw] w-auto h-auto object-contain transition-transform duration-75 select-none pointer-events-auto rounded-lg shadow-2xl"
             draggable="false">
    </div>

    {{-- Bottom Bar / Mobile Floating Controls --}}
    <div class="relative z-20 flex items-center justify-center gap-2 px-4 py-2 bg-slate-900/70 border-t border-white/10 sm:hidden">
        <span class="text-xs font-semibold text-slate-300 mr-2" x-text="zoomPercent"></span>
        <button type="button" @click="zoomOut()" class="p-2 rounded-lg bg-white/10 text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
        </button>
        <button type="button" @click="reset()" class="px-2.5 py-1 text-xs rounded-lg bg-white/10 text-white font-medium">Reset</button>
        <button type="button" @click="zoomIn()" class="p-2 rounded-lg bg-white/10 text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </button>
        <button type="button" @click="close()" class="p-2 rounded-lg bg-rose-600/80 text-white ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>

<script>
// ponytail: Alpine component for image viewer with zero external JS dependencies
function imageViewer() {
    return {
        isOpen: false,
        items: [],
        currentIndex: 0,
        scale: 1,
        minScale: 0.5,
        maxScale: 5.0,
        panX: 0,
        panY: 0,
        isDragging: false,
        startX: 0,
        startY: 0,
        isLoading: false,
        hasError: false,
        isFullscreen: false,
        initialPinchDistance: null,
        initialScale: 1,

        get currentItem() {
            return this.items[this.currentIndex] || { url: '', title: '' };
        },

        get zoomPercent() {
            return Math.round(this.scale * 100) + '%';
        },

        open(detail) {
            if (!detail) return;
            if (Array.isArray(detail.items) && detail.items.length > 0) {
                this.items = detail.items;
                this.currentIndex = Math.max(0, Math.min(detail.index || 0, this.items.length - 1));
            } else if (detail.url) {
                this.items = [{ url: detail.url, title: detail.title || 'Foto' }];
                this.currentIndex = 0;
            } else {
                return;
            }

            this.isOpen = true;
            this.reset();
            this.isLoading = true;
            this.hasError = false;
            document.body.style.overflow = 'hidden';
        },

        close() {
            if (document.fullscreenElement) {
                document.exitFullscreen().catch(() => {});
            }
            this.isOpen = false;
            this.reset();
            document.body.style.overflow = '';
        },

        onImageLoaded() {
            this.isLoading = false;
            this.hasError = false;
        },

        onImageError() {
            this.isLoading = false;
            this.hasError = true;
        },

        retry() {
            this.isLoading = true;
            this.hasError = false;
            const current = this.currentItem.url;
            this.items[this.currentIndex].url = '';
            this.$nextTick(() => {
                this.items[this.currentIndex].url = current + (current.includes('?') ? '&' : '?') + '_t=' + Date.now();
            });
        },

        next() {
            if (this.items.length <= 1) return;
            this.currentIndex = (this.currentIndex + 1) % this.items.length;
            this.reset();
            this.isLoading = true;
            this.hasError = false;
        },

        prev() {
            if (this.items.length <= 1) return;
            this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
            this.reset();
            this.isLoading = true;
            this.hasError = false;
        },

        zoomIn() {
            this.setScale(this.scale * 1.3);
        },

        zoomOut() {
            this.setScale(this.scale / 1.3);
        },

        setScale(newScale) {
            const clamped = Math.min(this.maxScale, Math.max(this.minScale, newScale));
            this.scale = parseFloat(clamped.toFixed(2));
            if (this.scale <= 1) {
                this.panX = 0;
                this.panY = 0;
            }
        },

        reset() {
            this.scale = 1;
            this.panX = 0;
            this.panY = 0;
            this.isDragging = false;
        },

        toggleZoom() {
            if (this.scale > 1) {
                this.reset();
            } else {
                this.setScale(2.5);
            }
        },

        toggleFullscreen() {
            const el = this.$el;
            if (!document.fullscreenElement) {
                if (el.requestFullscreen) {
                    el.requestFullscreen().then(() => { this.isFullscreen = true; }).catch(() => {});
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => { this.isFullscreen = false; }).catch(() => {});
                }
            }
        },

        onWheel(e) {
            if (e.deltaY < 0) {
                this.zoomIn();
            } else {
                this.zoomOut();
            }
        },

        startDrag(e) {
            if (e.button !== 0 || this.scale <= 1) return;
            this.isDragging = true;
            this.startX = e.clientX - this.panX;
            this.startY = e.clientY - this.panY;
        },

        onDrag(e) {
            if (!this.isDragging || this.scale <= 1) return;
            this.panX = e.clientX - this.startX;
            this.panY = e.clientY - this.startY;
        },

        stopDrag() {
            this.isDragging = false;
        },

        onTouchStart(e) {
            if (e.touches.length === 1 && this.scale > 1) {
                this.isDragging = true;
                this.startX = e.touches[0].clientX - this.panX;
                this.startY = e.touches[0].clientY - this.panY;
            } else if (e.touches.length === 2) {
                this.isDragging = false;
                this.initialPinchDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                this.initialScale = this.scale;
            }
        },

        onTouchMove(e) {
            if (e.touches.length === 1 && this.isDragging && this.scale > 1) {
                this.panX = e.touches[0].clientX - this.startX;
                this.panY = e.touches[0].clientY - this.startY;
            } else if (e.touches.length === 2 && this.initialPinchDistance) {
                const currentDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                const factor = currentDistance / this.initialPinchDistance;
                this.setScale(this.initialScale * factor);
            }
        },

        onTouchEnd(e) {
            if (e.touches.length === 0) {
                this.isDragging = false;
                this.initialPinchDistance = null;
            } else if (e.touches.length === 1 && this.scale > 1) {
                this.isDragging = true;
                this.startX = e.touches[0].clientX - this.panX;
                this.startY = e.touches[0].clientY - this.panY;
                this.initialPinchDistance = null;
            }
        }
    };
}

// Global invocation helper
window.openImageViewer = function(items, index = 0) {
    let payload = [];
    if (typeof items === 'string') {
        payload = [{ url: items, title: 'Foto' }];
    } else if (Array.isArray(items)) {
        payload = items.map(it => typeof it === 'string' ? { url: it, title: 'Foto' } : it);
    } else if (items && items.url) {
        payload = [items];
    }
    window.dispatchEvent(new CustomEvent('open-image-viewer', {
        detail: {
            items: payload,
            index: index
        }
    }));
};
</script>
