@props(['product'])

<div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:-translate-y-0.5"
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
     onmouseenter="if (window.showPreview) showPreview(this)"
     onmouseleave="if (window.onCardLeave) onCardLeave(this)">

    <div class="aspect-square bg-gray-50 flex items-center justify-center p-4 relative overflow-hidden">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                     loading="lazy"
                     onerror="this.onerror=null; var emoji=this.dataset.fallback||'✨'; this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-5xl select-none\'>'+emoji+'</div>';"
                     data-fallback="@switch($product->category->slug ?? '')@case('mens-t-shirt')👕@break@case('womens-t-shirt')👚@break@case('bags')👜@break@default✨@endswitch">
            @else
                <div class="w-full h-full flex items-center justify-center text-5xl select-none">
                    @switch($product->category->slug ?? '')
                        @case('mens-t-shirt') 👕 @break
                        @case('womens-t-shirt') 👚 @break
                        @case('bags') 👜 @break
                        @default ✨
                    @endswitch
                </div>
            @endif
        </a>

        @if($product->stock_status === 'out_of_stock')
            <div class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-10">
                <span class="bg-gray-900 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">Out of Stock</span>
            </div>
        @endif

        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
            @if($product->created_at->diffInDays(now()) < 7 && $product->stock_status !== 'out_of_stock')
                <span class="bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">New</span>
            @endif
            @if($product->discount_price && $product->stock_status !== 'out_of_stock')
                @php
                    $discountPct = $product->discount_type === 'percentage'
                        ? round($product->discount_price)
                        : round((1 - $product->discount_price / $product->price) * 100);
                @endphp
                <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">-{{ $discountPct }}%</span>
            @endif
        </div>

        {{-- Hover actions --}}
        <div class="absolute top-2.5 right-2.5 flex flex-col gap-1.5 opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 z-10">
            <button type="button"
                    onclick="if (window.showPreview) { event.stopPropagation(); showPreview(this.closest('[data-slug]')); }"
                    class="w-9 h-9 rounded-full bg-white shadow-md hover:shadow-lg flex items-center justify-center transition-all hover:scale-110"
                    title="Quick view">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
            @auth
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" onclick="event.stopPropagation()">
                    @csrf
                    <button type="submit"
                            class="w-9 h-9 rounded-full bg-white shadow-md hover:shadow-lg flex items-center justify-center transition-all hover:scale-110"
                            title="{{ $product->isInWishlist(auth()->id()) ? 'Remove from wishlist' : 'Add to wishlist' }}">
                        <svg class="w-4 h-4 {{ $product->isInWishlist(auth()->id()) ? 'text-red-500 fill-red-500' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <div class="p-3.5 sm:p-4">
        <div class="space-y-2">
            <div class="flex items-center gap-1.5">
                @if($product->category)
                    <span class="text-[10px] uppercase tracking-wider text-indigo-500 font-semibold">{{ $product->category->name }}</span>
                @endif
                @if($product->brand)
                    <span class="w-0.5 h-0.5 rounded-full bg-gray-300"></span>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $product->brand }}</span>
                @endif
            </div>

            <a href="{{ route('products.show', $product->slug) }}">
                <h3 class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2 hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
            </a>

            @if($product->avg_rating > 0)
                <div class="flex items-center gap-1">
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= round($product->avg_rating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-[10px] text-gray-400">({{ $product->reviews_count }})</span>
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-x-1.5 gap-y-1 pt-1">
                <div class="flex items-center gap-1.5 min-w-0">
                    @if($product->discount_price)
                        <span class="text-base font-bold text-gray-900">{{ formatPrice($product->final_price) }}</span>
                        <span class="text-xs text-gray-400 line-through">{{ formatPrice($product->price) }}</span>
                    @else
                        <span class="text-base font-bold text-gray-900">{{ formatPrice($product->price) }}</span>
                    @endif
                </div>
                @if($product->stock_status !== 'out_of_stock')
                    @if($product->stock_status === 'low_stock')
                        <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full whitespace-nowrap">Low Stock</span>
                    @else
                        <span class="flex items-center gap-1 text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full whitespace-nowrap">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            In Stock
                        </span>
                    @endif
                @endif
            </div>
        </div>

        @if($product->stock_status !== 'out_of_stock')
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Add to Cart
                </button>
            </form>
        @else
            <div class="mt-3 w-full py-2.5 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg text-center cursor-not-allowed">
                Out of Stock
            </div>
        @endif
    </div>
</div>
