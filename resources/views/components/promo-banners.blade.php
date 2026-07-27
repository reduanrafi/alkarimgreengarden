@props(['carouselBanners' => [], 'fixedBanner' => null])

@if($carouselBanners->count() > 0 || $fixedBanner)
    @php
        $carouselData = $carouselBanners->map(fn($b) => [
            'id' => $b->id,
            'title' => $b->title,
            'image' => asset('storage/' . $b->image),
            'button_text' => $b->button_text,
            'redirect_url' => $b->redirect_url,
        ])->values();
    @endphp

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 scroll-fade-in">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
            @if($carouselBanners->count() > 0)
                <div class="@if($fixedBanner) md:col-span-7 lg:col-span-8 @else md:col-span-12 @endif">
                    <div x-data="{
                        current: 0,
                        items: {{ Illuminate\Support\Js::from($carouselData) }},
                        paused: false,
                        interval: null,
                        touchStartX: 0,
                        touchEndX: 0,
                        init() { this.start(); },
                        start() { this.interval = setInterval(() => { if (!this.paused) this.next(); }, 4000); },
                        stop() { clearInterval(this.interval); this.interval = null; },
                        next() { this.current = (this.current + 1) % this.items.length; },
                        prev() { this.current = (this.current - 1 + this.items.length) % this.items.length; },
                        goTo(i) { this.current = i; this.stop(); this.start(); },
                        touchStart(e) { this.touchStartX = e.touches[0].clientX; },
                        touchEnd(e) {
                            this.touchEndX = e.changedTouches[0].clientX;
                            const diff = this.touchStartX - this.touchEndX;
                            if (Math.abs(diff) > 50) { if (diff > 0) this.next(); else this.prev(); }
                        }
                    }" class="relative w-full h-[260px] sm:h-[340px] lg:h-[400px] rounded-2xl overflow-hidden shadow-md bg-gray-100 group"
                       @mouseenter="paused = true" @mouseleave="paused = false"
                       @touchstart.prevent="touchStart($event)" @touchend.prevent="touchEnd($event)">

                        <template x-for="(banner, index) in items" :key="banner.id">
                            <div x-show="current === index"
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0">
                                <a :href="banner.redirect_url || '#'" :title="banner.title" class="block w-full h-full">
                                    <img :src="banner.image" :alt="banner.title"
                                         class="w-full h-full object-cover" loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-7">
                                        <h3 class="text-white text-base sm:text-lg lg:text-xl font-bold drop-shadow-lg leading-snug" x-text="banner.title"></h3>
                                        <template x-if="banner.button_text">
                                            <span class="inline-block mt-2.5 px-4 py-1.5 sm:px-5 sm:py-2 bg-white/90 text-gray-900 text-xs sm:text-sm font-semibold rounded-full shadow-md hover:bg-white transition" x-text="banner.button_text"></span>
                                        </template>
                                    </div>
                                </a>
                            </div>
                        </template>

                        <button @click="prev()" class="absolute left-2 sm:left-3 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center text-gray-700 hover:bg-white shadow-md transition z-10 opacity-0 group-hover:opacity-100 focus:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="next()" class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center text-gray-700 hover:bg-white shadow-md transition z-10 opacity-0 group-hover:opacity-100 focus:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        <div class="absolute bottom-2.5 sm:bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10">
                            <template x-for="(banner, index) in items" :key="'dot-'+banner.id">
                                <button @click="goTo(index)" class="h-1.5 rounded-full transition-all duration-300"
                                        :class="current === index ? 'w-5 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/70'"></button>
                            </template>
                        </div>
                    </div>
                </div>
            @endif

            @if($fixedBanner)
                <div class="@if($carouselBanners->count() > 0) md:col-span-5 lg:col-span-4 @else md:col-span-12 @endif">
                    <a href="{{ $fixedBanner->redirect_url ?: '#' }}"
                       class="block relative w-full h-[260px] sm:h-[340px] lg:h-[400px] rounded-2xl overflow-hidden shadow-md bg-gray-100 group">
                        <img src="{{ asset('storage/' . $fixedBanner->image) }}"
                             alt="{{ $fixedBanner->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-7">
                            <h3 class="text-white text-base sm:text-lg lg:text-xl font-bold drop-shadow-lg leading-snug">{{ $fixedBanner->title }}</h3>
                            @if($fixedBanner->button_text)
                                <span class="inline-block mt-2.5 px-4 py-1.5 sm:px-5 sm:py-2 bg-white/90 text-gray-900 text-xs sm:text-sm font-semibold rounded-full shadow-md group-hover:bg-white transition">{{ $fixedBanner->button_text }}</span>
                            @endif
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </section>
@endif
