@props(['categories' => [], 'banner' => null])

@php
    $heroBanners = \App\Models\Banner::hero()->active()->orderBy('display_order')->get();
    $heroTitle = $banner?->title ?? 'Discover Your Style';
    $heroSubtitle = 'Premium fashion curated for those who dare to be different.';
@endphp

<section class="relative bg-white overflow-hidden">
    @if($heroBanners->count() > 0)
        {{-- Hero Carousel --}}
        <div x-data="{
            current: 0,
            items: {{ Illuminate\Support\Js::from($heroBanners->values()->map(fn($b) => [
                'id' => $b->id,
                'title' => $b->title,
                'subtitle' => $b->subtitle ?? '',
                'image' => $b->image ? asset('storage/' . $b->image) : '',
                'button_text' => $b->button_text,
                'redirect_url' => $b->redirect_url,
            ])) }},
            interval: null,
            paused: false,
            startX: 0,
            endX: 0,
            init() { this.start(); },
            start() { this.interval = setInterval(() => { if (!this.paused) this.next(); }, 4000); },
            stop() { clearInterval(this.interval); this.interval = null; },
            next() { this.current = (this.current + 1) % this.items.length; },
            prev() { this.current = (this.current - 1 + this.items.length) % this.items.length; },
            goTo(i) { this.current = i; this.stop(); this.start(); },
            touchStart(e) { this.startX = e.touches ? e.touches[0].clientX : e.clientX; },
            touchEnd(e) {
                this.endX = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
                const diff = this.startX - this.endX;
                if (Math.abs(diff) > 50) { diff > 0 ? this.next() : this.prev(); }
            }
        }" class="relative w-full group" style="min-height: 65vh; max-height: 80vh;"
           @mouseenter="paused = true" @mouseleave="paused = false"
           @touchstart.passive="touchStart($event)" @touchend.passive="touchEnd($event)">

            <template x-for="(item, index) in items" :key="item.id">
                <div x-show="current === index"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-105"
                     class="absolute inset-0">
                    <div class="relative w-full h-full" style="min-height: 65vh;">
                        <template x-if="item.image">
                            <img :src="item.image" :alt="item.title" loading="lazy" class="w-full h-full absolute inset-0 object-cover select-none pointer-events-none">
                        </template>
                        <template x-if="!item.image">
                            <div class="w-full h-full absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900"></div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 via-40% to-transparent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                <div class="max-w-xl">
                                    <p class="text-indigo-300 text-sm sm:text-base font-medium tracking-wider uppercase mb-3" x-text="'New Collection ' + new Date().getFullYear()"></p>
                                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 font-serif" x-text="item.title"></h2>
                                    <p class="text-base text-gray-200 mb-6 max-w-md leading-relaxed" x-text="item.subtitle || 'Discover premium fashion that speaks your style.'"></p>
                                    <template x-if="item.button_text">
                                        <a :href="item.redirect_url || '{{ route('products.index') }}'"
                                           class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-gray-900 font-semibold rounded-full shadow-xl hover:bg-gray-100 hover:shadow-2xl transition-all active:scale-95 text-sm sm:text-base">
                                            <span x-text="item.button_text"></span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </a>
                                    </template>
                                    <template x-if="!item.button_text">
                                        <a href="{{ route('products.index') }}"
                                           class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-gray-900 font-semibold rounded-full shadow-xl hover:bg-gray-100 hover:shadow-2xl transition-all active:scale-95 text-sm sm:text-base">
                                            Shop Collection
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Nav Arrows --}}
            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/40 shadow-lg transition z-10 opacity-0 group-hover:opacity-100" :class="{'opacity-100': paused}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/40 shadow-lg transition z-10 opacity-0 group-hover:opacity-100" :class="{'opacity-100': paused}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                <template x-for="(item, index) in items" :key="'dot-'+item.id">
                    <button @click="goTo(index)" class="h-2 rounded-full transition-all duration-300"
                            :class="current === index ? 'w-6 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'"></button>
                </template>
            </div>
        </div>
    @else
        {{-- Fallback Hero --}}
        <div class="relative bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 overflow-hidden" style="min-height: 65vh;">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 30px 30px;"></div>
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-xl">
                        <span class="inline-block px-4 py-1.5 bg-indigo-500/20 backdrop-blur-sm text-indigo-300 text-xs font-semibold rounded-full border border-indigo-400/20 mb-5">Premium Collection</span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 font-serif">{{ $heroTitle }}</h1>
                        <p class="text-base text-gray-300 mb-8 max-w-md leading-relaxed">{{ $heroSubtitle }}</p>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-gray-900 font-semibold rounded-full shadow-xl hover:bg-gray-100 hover:shadow-2xl transition-all active:scale-95 text-sm sm:text-base">
                            Shop Collection
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-16 -right-16 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -top-16 -left-16 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>
    @endif
</section>
