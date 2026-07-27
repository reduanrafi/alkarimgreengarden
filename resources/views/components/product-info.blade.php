@props(['product'])

<div class="space-y-6">
    <div>
        <a href="{{ route('products.category', $product->category->slug) }}"
           class="inline-flex items-center gap-1.5 text-xs uppercase tracking-wider text-indigo-600 hover:text-indigo-700 font-semibold bg-indigo-50 px-3 py-1 rounded-full">
            {{ $product->category->name }}
        </a>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mt-3 leading-tight">{{ $product->name }}</h1>
        <div class="flex flex-wrap items-center gap-3 mt-3">
            @if($product->avg_rating > 0)
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($product->avg_rating) ? 'text-amber-400' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                    <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
                </div>
            @endif
            @if($product->brand)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-600 text-xs font-medium">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $product->brand }}
                </span>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-3">
        @if($product->discount_price)
            <div class="text-3xl font-bold text-indigo-600">{{ formatPrice($product->discount_price) }}</div>
            <div class="text-lg text-gray-400 line-through">{{ formatPrice($product->price) }}</div>
            <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">-{{ round((1 - $product->discount_price / $product->price) * 100) }}% OFF</span>
        @else
            <div class="text-3xl font-bold text-indigo-600">{{ formatPrice($product->price) }}</div>
        @endif
    </div>

    @if($product->id)
    <div class="text-xs text-gray-400 flex items-center gap-2">
        <span>SKU: <span class="font-mono text-gray-500">FSN-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span></span>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-4 text-sm bg-gray-50 rounded-xl p-4">
        @if($product->fabric)
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wider">Fabric</span>
                <p class="font-medium text-gray-900 mt-0.5">{{ $product->fabric }}</p>
            </div>
        @endif
        @if($product->color)
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wider">Color</span>
                <p class="font-medium text-gray-900 mt-0.5">
                    <span class="inline-block w-4 h-4 rounded-full border border-gray-300 align-middle mr-1.5" style="background-color: {{ strtolower($product->color) }}"></span>
                    {{ $product->color }}
                </p>
            </div>
        @endif
        @if($product->print)
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wider">Print</span>
                <p class="font-medium text-gray-900 mt-0.5">{{ $product->print }}</p>
            </div>
        @endif
        @if($product->size)
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wider">Sizes</span>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    @foreach(explode(', ', $product->size) as $size)
                        <span class="px-2.5 py-1 bg-white border border-gray-200 rounded-md text-xs font-medium text-gray-700 hover:border-indigo-300 cursor-pointer transition">{{ trim($size) }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-2">
        <span class="text-gray-400 text-sm">Stock Status:</span>
        @if($product->stock > 10)
            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                In Stock ({{ $product->stock }} available)
            </span>
        @elseif($product->stock > 0)
            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-amber-600">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Low Stock ({{ $product->stock }} left)
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                Out of Stock
            </span>
        @endif
    </div>

    @if($product->description)
        <div>
            <span class="text-gray-400 text-xs uppercase tracking-wider">Description</span>
            <p class="text-gray-600 text-sm mt-1.5 leading-relaxed">{{ $product->description }}</p>
        </div>
    @endif
</div>