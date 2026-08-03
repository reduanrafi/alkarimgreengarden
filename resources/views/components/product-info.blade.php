@props(['product'])

@php
    $colorSwatch = match (strtolower((string) $product->color)) {
        'white' => '#ffffff',
        'black' => '#1a1a1a',
        'grey', 'gray' => '#9ca3af',
        'navy', 'navy blue' => '#1f2a44',
        'olive green' => '#6b8e23',
        'brown' => '#8b5a2b',
        'pink' => '#f9a8d4',
        'red' => '#dc2626',
        'multi' => '#e0a13a',
        'blue' => '#2563eb',
        'gold' => '#d4a017',
        default => null,
    };
    $stock = $product->stock_status;
    $discountPct = 0;
    if ($product->discount_price) {
        $discountPct = $product->discount_type === 'percentage'
            ? round($product->discount_price)
            : round((1 - $product->discount_price / $product->price) * 100);
    }
@endphp

<div class="space-y-5">
    <div>
        <a href="{{ route('products.category', $product->category->slug) }}" class="gg-pdp-pill">
            <span>{{ $product->category->name }}</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <h1 class="gg-pdp-title text-2xl sm:text-3xl mt-3">{{ $product->name }}</h1>

        <div class="flex flex-wrap items-center gap-x-3 gap-y-2 mt-3">
            @if($product->avg_rating > 0)
                <span class="inline-flex items-center gap-1.5 text-sm">
                    <span class="flex gap-0.5 text-[#e0a13a]">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($product->avg_rating) ? 'fill-current' : 'fill-[#e6e9e2]' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </span>
                    <span class="font-bold text-[#22281f]">{{ number_format($product->avg_rating, 1) }}</span>
                    <a href="#reviews" class="text-[#5b6259] hover:text-[#1f5c3f] transition">({{ $product->reviews_count }} review{{ $product->reviews_count === 1 ? '' : 's' }})</a>
                </span>
            @endif
            @if($product->brand)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#e4efe4] text-[#1f5c3f] text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $product->brand }}
                </span>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="gg-pdp-price text-3xl">{{ formatPrice($product->final_price) }}</div>
        @if($product->discount_price && $product->final_price < $product->price)
            <div class="gg-pdp-price-old text-lg">{{ formatPrice($product->price) }}</div>
            <span class="gg-pdp-off">-{{ $discountPct }}%</span>
        @endif
    </div>

    @if($product->id)
        <div class="text-xs text-[#5b6259] flex items-center gap-2">
            <span>SKU: <span class="font-mono font-semibold text-[#22281f]">GG-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span></span>
        </div>
    @endif

    @if($product->fabric || $product->color || $product->print || $product->size)
        <div class="grid grid-cols-2 gap-3 text-sm">
            @if($product->fabric)
                <div class="gg-pdp-spec px-4 py-3">
                    <span class="k block">Fabric</span>
                    <p class="v mt-1">{{ $product->fabric }}</p>
                </div>
            @endif
            @if($product->color)
                <div class="gg-pdp-spec px-4 py-3">
                    <span class="k block">Color</span>
                    <p class="v mt-1 flex items-center gap-2">
                        @if($colorSwatch)
                            <span class="inline-block w-3.5 h-3.5 rounded-full border border-[#e6e9e2]" style="background-color: {{ $colorSwatch }}"></span>
                        @endif
                        {{ $product->color }}
                    </p>
                </div>
            @endif
            @if($product->print)
                <div class="gg-pdp-spec px-4 py-3">
                    <span class="k block">Print</span>
                    <p class="v mt-1">{{ $product->print }}</p>
                </div>
            @endif
            @if($product->size)
                <div class="gg-pdp-spec px-4 py-3">
                    <span class="k block">Size</span>
                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        @foreach(explode(', ', $product->size) as $size)
                            <span class="gg-pdp-chip">{{ trim($size) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="flex items-center gap-2">
        <span class="text-sm text-[#5b6259]">Availability:</span>
        @if($stock === 'out_of_stock')
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#b91c1c]">
                <span class="w-2 h-2 rounded-full bg-[#dc2626] animate-pulse"></span>
                Out of Stock
            </span>
        @elseif($stock === 'low_stock')
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#b45309]">
                <span class="w-2 h-2 rounded-full bg-[#f59e0b]"></span>
                Low Stock ({{ $product->stock }} left)
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1f5c3f]">
                <span class="w-2 h-2 rounded-full bg-[#3f8a5c]"></span>
                In Stock ({{ $product->stock }} available)
            </span>
        @endif
    </div>

    @if($product->description)
        <div class="pt-1">
            <span class="k block text-[10.5px] font-bold tracking-[0.08em] uppercase text-[#5b6259]">Description</span>
            <p class="text-sm text-[#5b6259] mt-1.5 leading-relaxed">{{ $product->description }}</p>
        </div>
    @endif
</div>
