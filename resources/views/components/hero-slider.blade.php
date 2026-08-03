@props(['heroBanners' => collect()])

@php
    $heroBanners = $heroBanners ?? collect();
    $brand = config('app.name', 'Green Garden');
@endphp

@if($heroBanners->count() > 0)
    <section class="swiper hero-slider">
        <div class="swiper-wrapper">
            @foreach($heroBanners as $banner)
                <div class="swiper-slide hero-slide"
                     @if($banner->image) style="background-image:url('{{ asset('storage/' . $banner->image) }}')" @endif>
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
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
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
