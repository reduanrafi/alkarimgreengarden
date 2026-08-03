@props(['categories' => collect()])

<aside class="cat-sidebar">
    <span class="eyebrow">Browse</span>
    <h3>Shop by Category</h3>
    <ul class="cat-list">
        @forelse($categories as $category)
            <li>
                <a href="{{ route('products.category', $category->slug) }}">
                    <span class="ico">{{ categoryEmoji($category->slug, $category->name) }}</span>
                    {{ $category->name }}
                    <span class="arrow">→</span>
                </a>
            </li>
        @empty
            <li>
                <a href="{{ route('products.index') }}">
                    <span class="ico">🌿</span>
                    All Products
                    <span class="arrow">→</span>
                </a>
            </li>
        @endforelse
        <li>
            <a href="{{ route('products.index') }}">
                <span class="ico">🛍️</span>
                All Products
                <span class="arrow">→</span>
            </a>
        </li>
        <li>
            <a href="{{ route('products.index', ['discounted' => 1]) }}" style="color:#c1521f;">
                <span class="ico" style="background:#fbe6da;">🔥</span>
                Sale
                <span class="arrow">→</span>
            </a>
        </li>
    </ul>
</aside>
