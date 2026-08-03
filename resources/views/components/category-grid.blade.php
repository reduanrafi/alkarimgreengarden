@props(['categories' => collect()])

@php
    $categories = $categories ?? collect();
@endphp

<section class="section">
    <div class="gg-container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Most Loved</span>
                <h2>Top Selling Categories</h2>
            </div>
            <a href="{{ route('products.index') }}" class="view-all">
                View All Categories
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($categories->count() > 0)
            <div class="top-cats-grid">
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}" class="top-cat-card">
                        @if($category->image)
                            <span class="thumb">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" loading="lazy"
                                     data-emoji-fallback="{{ categoryEmoji($category->slug, $category->name) }}">
                            </span>
                        @else
                            <span class="thumb-emoji">{{ categoryEmoji($category->slug, $category->name) }}</span>
                        @endif
                        <div class="label">
                            <h4>{{ $category->name }}</h4>
                            <span>{{ number_format($category->products_count) }} products</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <a href="{{ route('products.index') }}" class="top-cat-card">
                <span class="thumb-emoji">🌿</span>
                <div class="label">
                    <h4>All Products</h4>
                    <span>Shop the collection</span>
                </div>
            </a>
        @endif
    </div>
</section>
