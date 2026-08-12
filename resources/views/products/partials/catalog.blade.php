@if($products->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <div class="mt-8" data-pagination>
        {{ $products->links() }}
    </div>
@else
    {{-- Empty State --}}
    <div class="text-center py-16 bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#e4efe4] mb-5">
            <svg class="w-10 h-10 text-[#6fae6e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-1.5">No Products Found</h3>
        <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">
            We couldn't find any products matching your filters. Try adjusting or clearing them.
        </p>
        <button type="button" data-clear-filters
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#1f5c3f] text-white text-sm font-semibold rounded-lg hover:bg-[#173d2b] transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            Clear Filters
        </button>
    </div>
@endif
