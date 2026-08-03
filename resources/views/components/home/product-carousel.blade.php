@props([
    'eyebrow' => '',
    'title' => '',
    'subtitle' => '',
    'products' => collect(),
    'viewAllUrl' => null,
    'id' => null,
])

<section class="gg-section scroll-fade-in" @if($id) id="{{ $id }}" @endif>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="gg-section-head">
            <div>
                <span class="gg-eyebrow">{{ $eyebrow }}</span>
                <h2 class="font-display font-bold">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-sm text-ink-soft mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if($viewAllUrl)
                <a href="{{ $viewAllUrl }}" class="gg-view-all">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        @if($products && $products->count() > 0)
            <div class="swiper product-swiper">
                <div class="swiper-wrapper">
                    @foreach($products as $product)
                        <div class="swiper-slide h-full">
                            <x-home.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev dark"></div>
                <div class="swiper-button-next dark"></div>
            </div>
        @else
            <x-empty-state
                icon="products"
                title="Nothing here yet"
                message="Check back soon for new products."
                :action="$viewAllUrl ?: route('products.index')"
                actionText="Browse Products"
            />
        @endif
    </div>
</section>
