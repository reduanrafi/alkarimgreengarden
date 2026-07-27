@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@push('styles')
<style>
input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition">Products</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-indigo-600 transition">{{ $product->category->name }}</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14">
        <x-product-gallery :product="$product" />
        <div class="flex flex-col">
            <x-product-info :product="$product" />

            <div class="mt-8 pt-6 border-t border-gray-100">
                <div x-data="productCart()">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Quantity</span>
                        <div class="flex items-center gap-3 mt-2">
                            <button type="button" @click="decrement()" class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition font-medium text-lg">-</button>
                            <input type="number" x-model="qty" min="1" :max="maxQty"
                                   class="w-16 h-10 text-center border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                            <button type="button" @click="increment()" class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition font-medium text-lg">+</button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mt-6">
                        <button @click="addToCart({{ $product->id }})" :disabled="adding"
                                class="flex-1 px-8 py-3.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition text-sm shadow-sm hover:shadow-md flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                                x-html="adding ? `<svg class='animate-spin w-5 h-5' viewBox='0 0 24 24' fill='none'><circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'/><path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z'/></svg> Adding...` : `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z'/></svg> Add to Cart`">
                        </button>
                        <button @click="buyNow({{ $product->id }})" :disabled="buying"
                                class="flex-1 px-8 py-3.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition text-sm shadow-sm hover:shadow-md flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                                x-html="buying ? `<svg class='animate-spin w-5 h-5' viewBox='0 0 24 24' fill='none'><circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'/><path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z'/></svg> Processing...` : `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 10V3L4 14h7v7l9-11h-7z'/></svg> Buy Now`">
                        </button>
                        @auth
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-5 py-3.5 border border-gray-200 text-gray-600 hover:text-red-500 hover:border-red-200 rounded-xl transition text-sm flex items-center gap-2 hover:bg-red-50"
                                        title="{{ $product->isInWishlist(auth()->id()) ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                    <svg class="w-5 h-5 {{ $product->isInWishlist(auth()->id()) ? 'text-red-500 fill-red-500' : '' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="hidden sm:inline">{{ $product->isInWishlist(auth()->id()) ? 'Remove' : 'Wishlist' }}</span>
                                </button>
                            </form>
                        @endauth
                        <button type="button" onclick="shareProduct()"
                                class="px-4 py-3.5 border border-gray-200 text-gray-600 hover:text-indigo-600 hover:border-indigo-200 rounded-xl transition text-sm flex items-center gap-2 hover:bg-indigo-50"
                                title="Share this product">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span class="hidden sm:inline">Share</span>
                        </button>
                    </div>

                    <div x-show="added" x-cloak x-transition
                         class="mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="addedMsg"></span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="flex items-center gap-6 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Free Shipping
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Easy Returns
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Secure Checkout
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $specs = $product->productAttributeValues->groupBy(fn($pav) => $pav->attribute->name ?? 'General');
    @endphp

    @if($specs->count() > 0)
        <section class="mt-16 pt-12 border-t border-gray-200 scroll-fade-in">
            <div class="flex items-center gap-3 mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Specifications</h2>
                <span class="h-px flex-1 bg-gray-100"></span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                @foreach($specs as $attrName => $items)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 px-6 py-4">
                        <span class="font-medium text-gray-700 text-sm">{{ $attrName }}</span>
                        <span class="text-gray-600 text-sm sm:col-span-2">{{ $items->pluck('attributeValue.value')->implode(', ') }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if($related->count() > 0)
        <section class="mt-16 pt-12 border-t border-gray-200 scroll-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="inline-block w-6 h-px bg-indigo-300"></span>
                        <span class="text-xs uppercase tracking-[0.2em] text-indigo-500 font-medium">You May Also Like</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Related Products</h2>
                </div>
                <a href="{{ route('products.category', $product->category->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($related as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-16 pt-12 border-t border-gray-200 scroll-fade-in" x-data="recentlyViewed()" x-show="items.length > 0" x-cloak>
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="inline-block w-6 h-px bg-indigo-300"></span>
                    <span class="text-xs uppercase tracking-[0.2em] text-indigo-500 font-medium">Recently Viewed</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Your Recent Products</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <template x-for="item in items" :key="item.id">
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <a :href="'/products/' + item.slug" class="block">
                        <div class="image-zoom aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
                            <img :src="item.image ? '/storage/' + item.image : ''" :alt="item.name"
                                 class="w-full h-full object-contain" loading="lazy"
                                 x-on:error="$el.closest('.image-zoom').innerHTML = '<div class=\'text-5xl\'>✨</div>'">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 text-sm line-clamp-2" x-text="item.name"></h3>
                            <p class="text-indigo-600 font-bold text-sm mt-1.5" x-text="'$' + parseFloat(item.price).toFixed(2)"></p>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </section>

    <section class="mt-16 pt-12 border-t border-gray-200 scroll-fade-in">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Customer Reviews</h2>
            <span class="h-px flex-1 bg-gray-100"></span>
        </div>

        @php $reviews = $product->reviews()->with('user')->where('status', true)->latest()->get(); @endphp

        @if($reviews->count() > 0)
            <div class="flex flex-wrap items-center gap-4 mb-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-900">{{ number_format($product->avg_rating, 1) }}</div>
                    <div class="flex text-amber-400 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($product->avg_rating) ? 'fill-current' : 'fill-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $product->reviews_count }} review(s)</p>
                </div>
            </div>
        @endif

        @auth
            @php $userReview = $product->reviews()->where('user_id', auth()->id())->first(); @endphp
            @if(!$userReview)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                    <h3 class="font-bold text-gray-900 mb-4">Write a Review</h3>
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex flex-row-reverse justify-end gap-1" id="starRating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer">
                                    <label for="star{{ $i }}" class="cursor-pointer text-gray-200 peer-checked:text-amber-400 hover:text-amber-400 transition-colors text-2xl">
                                        ★
                                    </label>
                                @endfor
                            </div>
                            @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                            <textarea name="comment" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none resize-none" placeholder="Share your experience with this product..."></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">Submit Review</button>
                    </form>
                </div>
            @endif
        @endauth

        <div class="space-y-4">
            @forelse($reviews as $review)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition hover:shadow-md">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-600 flex items-center justify-center text-sm font-bold">{{ substr($review->user->name, 0, 1) }}</div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($review->user_id === auth()->id())
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete your review?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition px-2 py-1 rounded-lg hover:bg-red-50">Delete</button>
                            </form>
                        @endif
                    </div>
                    <div class="flex text-amber-400 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'fill-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    @if($review->comment)
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                    <div class="text-4xl mb-3">💬</div>
                    <p class="text-gray-400 text-sm">No reviews yet. Be the first to share your experience!</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('recentlyViewed', () => ({
        items: [],
        init() {
            const current = {{ $product->id }};
            const stored = localStorage.getItem('recentlyViewed');
            const parsed = stored ? JSON.parse(stored) : [];
            const ids = parsed.filter(id => id !== current).slice(0, 4);
            if (ids.length === 0) return;
            fetch(`/api/products/recent?ids=${ids.join(',')}`)
                .then(r => r.json())
                .then(data => { this.items = data; })
                .catch(() => {});
        }
    }));

    Alpine.data('productCart', () => ({
        qty: 1,
        maxQty: {{ $product->stock }},
        adding: false,
        buying: false,
        added: false,
        addedMsg: '',
        increment() {
            if (this.qty < this.maxQty) this.qty++;
        },
        decrement() {
            if (this.qty > 1) this.qty--;
        },
        async addToCart(productId) {
            this.adding = true;
            this.added = false;
            try {
                const form = new FormData();
                form.append('_token', '{{ csrf_token() }}');
                form.append('quantity', this.qty);
                const res = await fetch('/cart/add/' + productId, { method: 'POST', body: form });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                this.added = true;
                this.addedMsg = data.message || 'Added to cart!';
                window.dispatchEvent(new CustomEvent('cart-updated'));
            } catch(e) {
                this.added = true;
                this.addedMsg = 'Please login to add items to cart.';
            } finally {
                this.adding = false;
            }
        },
        async buyNow(productId) {
            this.buying = true;
            try {
                const form = new FormData();
                form.append('_token', '{{ csrf_token() }}');
                form.append('quantity', this.qty);
                const res = await fetch('/cart/add/' + productId, { method: 'POST', body: form });
                if (!res.ok) throw new Error('Failed');
                await res.json();
                window.location.href = '{{ route("checkout.create") }}';
            } catch(e) {
                window.location.href = '{{ route("login") }}';
            }
        }
    }));
});

function shareProduct() {
    const url = window.location.href;
    const title = '{{ $product->name }}';
    if (navigator.share) {
        navigator.share({ title, url });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const toast = document.createElement('div');
            toast.className = 'toast toast-success';
            toast.textContent = 'Link copied to clipboard!';
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(40px)'; toast.style.transition = 'all 0.3s ease-out'; setTimeout(() => toast.remove(), 300); }, 3000);
        });
    }
}

(function() {
    try {
        const key = 'recentlyViewed';
        const current = {{ $product->id }};
        const stored = localStorage.getItem(key);
        const ids = stored ? JSON.parse(stored) : [];
        const filtered = [current, ...ids.filter(id => id !== current)].slice(0, 8);
        localStorage.setItem(key, JSON.stringify(filtered));
    } catch(e) {}
})();
</script>
@endpush