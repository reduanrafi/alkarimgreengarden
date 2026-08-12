@props(['product'])

<div class="gg-card group cursor-pointer"
     data-id="{{ $product->id }}"
     data-name="{{ $product->name }}"
     data-price="{{ number_format($product->price, 2) }}"
     data-discount="{{ $product->discount_price ? number_format($product->discount_price, 2) : '' }}"
     data-price-formatted="{{ formatPrice($product->price) }}"
     data-discount-formatted="{{ $product->discount_price ? formatPrice($product->discount_price) : '' }}"
     data-category-name="{{ $product->category->name ?? '' }}"
     data-category-slug="{{ $product->category->slug ?? '' }}"
     data-fabric="{{ $product->fabric }}"
     data-color="{{ $product->color }}"
     data-print="{{ $product->print }}"
     data-size="{{ $product->size }}"
     data-stock="{{ $product->stock }}"
     data-description="{{ \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 200) }}"
     data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
     data-slug="{{ $product->slug }}"
     onclick="if(!event.target.closest('button, form, a')) window.location.href = '{{ route('products.show', $product->slug) }}'">

    <div class="gg-media">
        @if($product->stock_status === 'out_of_stock')
            <div class="absolute inset-0 z-[3] bg-white/75 backdrop-blur-[2px] flex items-center justify-center">
                <span class="bg-gray-900 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">Out of Stock</span>
            </div>
        @endif

        @php
            $discountPct = 0;
            if ($product->discount_price) {
                $discountPct = $product->discount_type === 'percentage'
                    ? round($product->discount_price)
                    : round((1 - $product->discount_price / $product->price) * 100);
            }
            $isNew = $product->created_at->diffInDays(now()) < 7;
        @endphp
        @if($product->discount_price && $product->stock_status !== 'out_of_stock')
            <span class="gg-tag" style="background:#c1521f;">-{{ $discountPct }}%</span>
        @elseif($isNew && $product->stock_status !== 'out_of_stock')
            <span class="gg-tag">New</span>
        @endif

        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            @php
                $emoji = '🌿';
            @endphp
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="fade-img w-full h-full object-cover"
                     loading="lazy"
                     data-emoji-fallback="{{ $emoji }}">
            @else
                <div class="w-full h-full flex items-center justify-center text-5xl select-none">{{ $emoji }}</div>
            @endif
        </a>

        @if($product->stock_status !== 'out_of_stock')
            <div class="absolute inset-x-3 bottom-3 z-20 grid grid-cols-3 gap-1.5 translate-y-2 opacity-0 transition-all duration-200 group-hover:translate-y-0 group-hover:opacity-100">
                <a href="{{ route('products.show', $product->slug) }}" class="rounded-lg bg-white/95 px-1 py-2 text-center text-[10px] font-semibold text-ink shadow-md transition hover:bg-brand-700 hover:text-white">View Details</a>
                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" x-data="wishlistToggle" @submit.prevent="toggle($event.target, $refs.btn)">
                        @csrf
                        <button type="submit" x-ref="btn" class="h-full w-full rounded-lg bg-white/95 px-1 py-2 text-[10px] font-semibold text-ink shadow-md transition hover:bg-brand-700 hover:text-white">Add to Wishlist</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg bg-white/95 px-1 py-2 text-center text-[10px] font-semibold text-ink shadow-md transition hover:bg-brand-700 hover:text-white">Add to Wishlist</a>
                @endauth
                <form action="{{ route('cart.add', $product->id) }}" method="POST" x-data="addToCart" @submit.prevent="submit($event.target)">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" :disabled="loading" class="h-full w-full rounded-lg bg-brand-700 px-1 py-2 text-[10px] font-semibold text-white shadow-md transition hover:bg-brand-900 disabled:opacity-70">Add to Cart</button>
                </form>
            </div>
        @endif
    </div>

    <div class="gg-body flex flex-col">
        <span class="gg-cat-label">{{ $product->category->name ?? '' }}</span>
        <a href="{{ route('products.show', $product->slug) }}">
            <h4 class="gg-title">{{ $product->name }}</h4>
        </a>
        <div class="gg-stars">
            @if($product->avg_rating > 0)
                @php
                    $rounded = round($product->avg_rating);
                    $full = $rounded;
                @endphp
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $full ? '' : 'opacity-30' }}">★</span>
                @endfor
                <span>({{ $product->reviews_count }})</span>
            @endif
        </div>
        <div class="gg-price-row mt-auto">
            <span class="gg-price">
                {{ formatPrice($product->final_price) }}
                @if($product->discount_price)
                    <small>{{ formatPrice($product->price) }}</small>
                @endif
            </span>
            @if($product->stock_status !== 'out_of_stock')
                <form action="{{ route('cart.add', $product->id) }}" method="POST"
                      x-data="addToCart" @submit.prevent="submit($event.target)">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="gg-add-cart" title="Add to cart" aria-label="Add {{ $product->name }} to cart">＋</button>
                </form>
            @endif
        </div>
    </div>
</div>
