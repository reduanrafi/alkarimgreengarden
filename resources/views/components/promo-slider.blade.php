@props(['carouselBanners' => collect(), 'fixedBanner' => null])

@php
    $carouselBanners = $carouselBanners ?? collect();
@endphp

{{-- Fixed Banner (priority) --}}
@if($fixedBanner)
    @php
        $fixedBannerBackground = preg_match('/^#(?:[a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})$/i', (string) $fixedBanner->background_color)
            ? $fixedBanner->background_color
            : '#e4efe4';
    @endphp

    <div class="promo-slider promo-fixed-banner" style="--promo-banner-background: {{ $fixedBannerBackground }};">
        <a href="{{ $fixedBanner->redirect_url ?: route('products.index') }}" class="promo-slide">
            <img src="{{ asset('storage/' . $fixedBanner->image) }}"
                 alt="{{ $fixedBanner->title }}"
                 class="promo-slide-img"
                 loading="lazy">
            <div class="promo-overlay"></div>
            <div class="promo-inner">
                <span class="eyebrow">Green Garden</span>
                <h3>{{ $fixedBanner->title }}</h3>
                @if($fixedBanner->button_text)
                    <span class="gg-btn-primary">{{ $fixedBanner->button_text }}</span>
                @else
                    <span class="gg-btn-primary">Shop Now</span>
                @endif
            </div>
        </a>
    </div>

{{-- Carousel Banners (fallback when no fixed banner) --}}
@elseif($carouselBanners->count() > 0)
    <div class="swiper promo-slider homepage-carousel-banner">
        <div class="swiper-wrapper">
            @foreach($carouselBanners as $banner)
                <div class="swiper-slide promo-slide">
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             alt="{{ $banner->title }}"
                             class="promo-slide-img"
                             loading="lazy">
                    @endif
                    <div class="promo-overlay"></div>
                    <div class="promo-inner">
                        <span class="eyebrow">Green Garden</span>
                        <h3>{{ $banner->title }}</h3>
                        @if($banner->button_text)
                            <a href="{{ $banner->redirect_url ?: route('products.index') }}" class="gg-btn-primary">
                                {{ $banner->button_text }}
                            </a>
                        @else
                            <a href="{{ $banner->redirect_url ?: route('products.index') }}" class="gg-btn-primary">Shop Now</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev" aria-label="Previous banner"></div>
        <div class="swiper-button-next" aria-label="Next banner"></div>
    </div>

{{-- Fallback promo card when nothing is configured --}}
@else
    <div class="promo-slider">
        <div class="promo-slide">
            <div class="promo-overlay" style="background: linear-gradient(150deg, var(--green-700) 0%, var(--green-900) 100%);"></div>
            <div class="promo-inner">
                <span class="eyebrow">New This Week</span>
                <h3>Fresh stock of our favourite products.</h3>
                <a href="{{ route('products.index') }}" class="gg-btn-primary">See New Arrivals</a>
            </div>
        </div>
    </div>
@endif
