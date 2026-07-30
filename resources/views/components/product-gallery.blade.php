@props(['product'])

<div x-data="{ activeImage: '{{ $product->image ? asset('storage/' . $product->image) : '' }}', zoomIn: false, zoomPos: { x: 50, y: 50 } }" class="sticky top-[88px] lg:top-[100px]">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden image-zoom relative cursor-crosshair" x-on:mousemove="zoomIn = true; zoomPos = { x: (($event.offsetX) / $el.offsetWidth) * 100, y: (($event.offsetY) / $el.offsetHeight) * 100 }" x-on:mouseleave="zoomIn = false">
        <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden rounded-[1px]">
            @if($product->image)
                <img :src="activeImage"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition-all duration-500"
                     :class="zoomIn ? 'scale-150' : 'scale-100'"
                     :style="zoomIn ? `transform-origin: ${zoomPos.x}% ${zoomPos.y}%` : ''"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
            @else
                <div class="text-8xl sm:text-9xl select-none">
                    @switch($product->category->slug ?? '')
                        @case('mens-t-shirt') 👕 @break
                        @case('womens-t-shirt') 👚 @break
                        @case('bags') 👜 @break
                        @default ✨
                    @endswitch
                </div>
            @endif
        </div>
    </div>

    @php
        $images = [];
        if ($product->image) $images[] = $product->image;
        if (!empty($product->images) && is_array($product->images)) {
            $images = array_merge($images, $product->images);
        }
    @endphp

    @if(count($images) > 1)
        <div class="flex gap-2.5 mt-4 overflow-x-auto pb-2 scrollbar-hide" x-on:mouseleave="zoomIn = false">
            @foreach($images as $idx => $img)
                <button @click="activeImage = '{{ asset('storage/' . $img) }}'; zoomIn = false"
                        class="shrink-0 w-[68px] h-[68px] sm:w-[76px] sm:h-[76px] rounded-xl border-2 overflow-hidden transition-all duration-200"
                        :class="activeImage === '{{ asset('storage/' . $img) }}' ? 'border-indigo-500 ring-2 ring-indigo-200 shadow-md' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm'">
                    <img src="{{ asset('storage/' . $img) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                </button>
            @endforeach
        </div>
    @endif
</div>
