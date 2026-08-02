@props(['product'])

@php
    $images = [];
    if ($product->image) $images[] = $product->image;
    if (!empty($product->gallery_images) && is_array($product->gallery_images)) {
        foreach ($product->gallery_images as $galleryImage) {
            if (!in_array($galleryImage, $images, true)) {
                $images[] = $galleryImage;
            }
        }
    }
    $imageUrls = array_map(fn ($img) => asset('storage/' . $img), $images);
    $fallbackEmoji = match ($product->category->slug ?? '') {
        'mens-t-shirt' => '👕',
        'womens-t-shirt' => '👚',
        'bags' => '👜',
        default => '✨',
    };
@endphp

<div x-data="productGallery()" class="sticky top-[88px] lg:top-[100px]">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden image-zoom relative cursor-crosshair group"
         x-on:mousemove="zoomIn = true; zoomPos = { x: (($event.offsetX) / $el.offsetWidth) * 100, y: (($event.offsetY) / $el.offsetHeight) * 100 }"
         x-on:mouseleave="zoomIn = false"
         x-on:click="openLightbox()">
        <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden rounded-[1px] relative">
            @if($product->image)
                <template x-if="imageLoaded">
                    <img :src="activeImage"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition-all duration-500 select-none"
                         :class="zoomIn ? 'scale-150' : 'scale-100'"
                         :style="zoomIn ? `transform-origin: ${zoomPos.x}% ${zoomPos.y}%` : ''">
                </template>

                <div x-show="!imageLoaded" class="absolute inset-0 animate-pulse bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center" x-cloak>
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                <img :src="activeImage"
                     class="hidden"
                     x-on:load="imageLoaded = true"
                     x-on:error="imageLoaded = false">
            @else
                <div class="text-8xl sm:text-9xl select-none">{{ $fallbackEmoji }}</div>
            @endif
        </div>

        <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-sm text-white text-[10px] font-medium px-2.5 py-1.5 rounded-full flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity" x-show="imageLoaded" x-cloak>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Click to zoom
        </div>
    </div>

    @if(count($images) > 1)
        <div class="flex gap-2.5 mt-4 overflow-x-auto pb-2 scrollbar-hide" x-on:mouseleave="zoomIn = false">
            @foreach($imageUrls as $idx => $url)
                <button @click.stop="setActive({{ $idx }})"
                        class="shrink-0 w-[68px] h-[68px] sm:w-[76px] sm:h-[76px] rounded-xl border-2 overflow-hidden transition-all duration-200"
                        :class="activeIndex === {{ $idx }} ? 'border-indigo-500 ring-2 ring-indigo-200 shadow-md' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm'">
                    <img src="{{ $url }}" alt="" class="w-full h-full object-cover" loading="lazy">
                </button>
            @endforeach
        </div>
    @endif

    {{-- Lightbox --}}
    <template x-teleport="body">
        <div x-show="lightboxOpen" x-cloak x-transition.opacity
             class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm flex items-center justify-center p-4"
             x-on:click.self="closeLightbox()"
             x-on:keydown.escape.window="closeLightbox()"
             x-on:keydown.left.window.prevent="prev()"
             x-on:keydown.right.window.prevent="next()"
             role="dialog" aria-modal="true">
            <button @click="closeLightbox()" class="absolute top-4 right-4 sm:top-6 sm:right-6 text-white/70 hover:text-white transition p-2" aria-label="Close">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <button @click="prev()" :disabled="activeIndex === 0"
                    class="absolute left-3 sm:left-6 text-white/70 hover:text-white transition p-2 disabled:opacity-30 disabled:cursor-not-allowed" aria-label="Previous image">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <div class="max-w-4xl w-full max-h-[85vh] flex items-center justify-center">
                <img :src="lightboxImage" :alt="'{{ addslashes($product->name) }}'" class="max-w-full max-h-[85vh] w-auto h-auto object-contain rounded-xl select-none">
            </div>

            <button @click="next()" :disabled="activeIndex >= {{ count($imageUrls) - 1 }}"
                    class="absolute right-3 sm:right-6 text-white/70 hover:text-white transition p-2 disabled:opacity-30 disabled:cursor-not-allowed" aria-label="Next image">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            <div class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 text-white/80 text-sm font-medium flex items-center gap-3">
                <span x-text="`${activeIndex + 1} / {{ count($imageUrls) }}`"></span>
                <span class="text-white/40 hidden sm:inline">·</span>
                <span class="hidden sm:inline text-white/40">Use ← → keys to navigate</span>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productGallery', () => ({
        images: @json($imageUrls),
        activeIndex: 0,
        zoomIn: false,
        zoomPos: { x: 50, y: 50 },
        imageLoaded: false,
        lightboxOpen: false,

        get activeImage() {
            return this.images[this.activeIndex] || '';
        },
        get lightboxImage() {
            return this.images[this.activeIndex] || '';
        },
        init() {
            this.imageLoaded = false;
        },
        setActive(index) {
            this.activeIndex = index;
            this.zoomIn = false;
            this.imageLoaded = false;
        },
        openLightbox() {
            if (!this.images.length) return;
            this.zoomIn = false;
            this.imageLoaded = false;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.style.overflow = '';
        },
        prev() {
            if (!this.lightboxOpen) return;
            if (this.activeIndex > 0) {
                this.setActive(this.activeIndex - 1);
            }
        },
        next() {
            if (!this.lightboxOpen) return;
            if (this.activeIndex < this.images.length - 1) {
                this.setActive(this.activeIndex + 1);
            }
        },
    }));
});
</script>
@endpush
