@props(['heroBanners' => collect()])

@php
    $heroBanners = $heroBanners ?? collect();
    $brand = config('app.name', 'Green Garden');
    $firstHeroImage = $heroBanners->first()?->image;
@endphp

@if($heroBanners->count() > 0)
    @if($firstHeroImage)
        @push('styles')
            <link rel="preload" as="image" href="{{ asset('storage/' . $firstHeroImage) }}" fetchpriority="high">
        @endpush
    @endif

    <section class="swiper hero-slider" role="region" aria-roledescription="carousel" aria-label="Featured promotions">
        <div id="hero-carousel-track" class="swiper-wrapper" aria-live="off">
            @foreach($heroBanners as $banner)
                <article class="swiper-slide hero-slide" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $heroBanners->count() }}">
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             alt=""
                             class="hero-slide-img"
                             @if($loop->first) loading="eager" fetchpriority="high" @else loading="lazy" @endif>
                    @endif
                    <div class="hero-content">
                        <span class="eyebrow">{{ $brand }}</span>
                        <h1>{{ $banner->title }}</h1>
                        @if($banner->short_description)
                            <p>{{ $banner->short_description }}</p>
                        @endif
                        <div class="hero-actions">
                            <a href="{{ $banner->redirect_url ?: route('products.index') }}" class="gg-btn-primary">
                                {{ $banner->button_text ?: 'Shop Now' }}
                            </a>
                            <a href="{{ route('products.index') }}" class="gg-btn-ghost">Explore Collection</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <button type="button" class="swiper-button-prev" aria-label="Previous banner" aria-controls="hero-carousel-track"></button>
        <button type="button" class="swiper-button-next" aria-label="Next banner" aria-controls="hero-carousel-track"></button>
    </section>
@else
    {{-- Fallback hero when no banners are configured --}}
    <section class="hero-slider">
        <div class="hero-slide" style="background: linear-gradient(120deg, var(--green-900) 0%, var(--green-700) 100%);">
            <div class="hero-content">
                <span class="eyebrow">{{ $brand }}</span>
                <h1>Bring nature home.</h1>
                <p>Hand-picked plants for every space — delivered healthy, potted, and ready to thrive.</p>
                <div class="hero-actions">
                    <a href="{{ route('products.index') }}" class="gg-btn-primary">Shop Now</a>
                    <a href="{{ route('products.index') }}" class="gg-btn-ghost">Explore Collection</a>
                </div>
            </div>
        </div>
    </section>
@endif
