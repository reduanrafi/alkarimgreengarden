@props(['categories' => []])

@if(count($categories) > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 scroll-fade-in">
    <div class="text-center mb-10">
        <span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 text-xs font-semibold rounded-full mb-3">Categories</span>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-serif">Shop by Category</h2>
        <p class="text-gray-400 text-sm mt-2">Find exactly what you're looking for</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
        @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}"
               class="group relative bg-[#FFF7E6] rounded-[18px] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                <div class="h-[180px] sm:h-[200px] flex items-center justify-center p-5 sm:p-6 skeleton-sm">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                             loading="lazy"
                             onload="this.classList.add('img-loaded')"
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300 fade-img relative">
                    @else
                        <div class="text-5xl sm:text-6xl group-hover:scale-110 transition-transform duration-300">
                            @switch($category->slug)
                                @case('mens-t-shirt') 👕 @break
                                @case('womens-t-shirt') 👚 @break
                                @case('bags') 👜 @break
                                @default ✨
                            @endswitch
                        </div>
                    @endif
                </div>
                <div class="pb-4 sm:pb-5 px-3 text-center">
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base leading-tight">{{ $category->name }}</h3>
                </div>
            </a>
        @endforeach
    </div>
</section>
@else
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 scroll-fade-in">
        <x-empty-state
            icon="categories"
            title="Categories coming soon"
            message="We're organizing our collection. Check back shortly to browse by category."
            :action="route('products.index')"
            actionText="Shop All Products"
        />
    </section>
@endif
