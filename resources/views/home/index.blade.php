@extends('layouts.app')

@section('title', config('app.name') . ' - Plants, Planters & Garden Essentials')
@section('meta_description', 'Discover hand-picked plants, planters and garden essentials. Shop our collection and bring a little more green into your life.')

@section('content')
    {{-- Hero Slider --}}
    <x-hero-slider :heroBanners="$heroBanners" />

    {{-- Split: Category Sidebar + Promo --}}
    <section class="split-section">
        <div class="gg-container split-grid">
            <x-category-sidebar :categories="$categories" />
            <x-promo-slider :carouselBanners="$carouselBanners" />
        </div>
    </section>

    {{-- Featured Products --}}
    <x-home.product-carousel
        eyebrow="Handpicked"
        title="Featured Products"
        subtitle="Our favourite picks for every space"
        :products="$featuredProducts"
        viewAllUrl="{{ route('products.index') }}"
    />

    {{-- Top Selling Categories --}}
    <x-category-grid :categories="$topCategories" />

    {{-- Best Sellers --}}
    <x-home.product-carousel
        eyebrow="Customer Favorites"
        title="Best Sellers"
        subtitle="The products our customers love most"
        :products="$bestSellers"
        viewAllUrl="{{ route('products.index', ['sort' => 'best_selling']) }}"
    />

    {{-- Trust Badges --}}
    <x-trust-badges />

    {{-- Newsletter --}}
    <x-newsletter />

    <x-product-preview />
@endsection
