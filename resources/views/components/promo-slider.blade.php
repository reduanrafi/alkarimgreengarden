@props(['carouselBanners' => collect()])

@php
    $carouselBanners = $carouselBanners ?? collect();
@endphp

@if($carouselBanners->count() > 0)
    <div class="swiper promo-slider">
        <div class="swiper-wrapper">
            @foreach($carouselBanners as $banner)
                <div class="swiper-slide promo-slide"
                     @if($banner->image) style="background-image:url('{{ asset('storage/' . $banner->image) }}')" @endif>
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
    </div>
@else
    {{-- Fallback promo card when no carousel banners are configured --}}
    <div class="promo-slider">
        <div class="promo-slide" style="background: linear-gradient(150deg, var(--green-700) 0%, var(--green-900) 100%);">
            <div class="promo-inner">
                <span class="eyebrow">New This Week</span>
                <h3>Fresh stock of our favourite products.</h3>
                <a href="{{ route('products.index') }}" class="gg-btn-primary">See New Arrivals</a>
            </div>
        </div>
    </div>
@endif
