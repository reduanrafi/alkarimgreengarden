@props(['categories' => []])

@if(count($categories) > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 scroll-fade-in">
    <div class="text-center mb-10">
        <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-full mb-3">Categories</span>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-serif">Shop by Category</h2>
        <p class="text-gray-400 text-sm mt-2">Find exactly what you're looking for</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
        @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}"
               class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                <div class="aspect-square bg-gradient-to-br from-gray-50 via-white to-gray-100 flex items-center justify-center @if($category->image) p-0 overflow-hidden @else p-5 @endif">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="text-4xl sm:text-5xl group-hover:scale-110 transition-transform duration-500">
                            @switch($category->slug)
                                @case('mens-t-shirt') 👕 @break
                                @case('womens-t-shirt') 👚 @break
                                @case('bags') 👜 @break
                                @default ✨
                            @endswitch
                        </div>
                    @endif
                </div>
                <div class="p-3 sm:p-4 text-center">
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">{{ $category->name }}</h3>
                    <p class="text-xs text-indigo-500 font-medium mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity">Shop Now &rarr;</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif
