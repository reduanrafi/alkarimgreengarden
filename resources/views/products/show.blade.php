@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 160))

@section('content')
@php
    $specRows = collect();
    $eavSpecs = $product->productAttributeValues->groupBy(fn ($pav) => $pav->attribute->name ?? 'General');
    foreach ($eavSpecs as $attrName => $items) {
        $specRows->push([$attrName, $items->pluck('attributeValue.value')->implode(', ')]);
    }
    if ($product->fabric) $specRows->push(['Light', $product->fabric]);
    if ($product->color) $specRows->push(['Color', $product->color]);
    if ($product->print) $specRows->push(['Type', $product->print]);
    if ($product->size) $specRows->push(['Size', $product->size]);

    $reviews = $product->reviews;
    $totalReviews = $reviews->count();
    $distribution = collect(range(5, 1))->mapWithKeys(fn ($star) => [$star => $reviews->where('rating', $star)->count()]);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    {{-- Breadcrumb --}}
    <nav class="gg-pdp-breadcrumb mb-6 sm:mb-8" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="sep">›</span>
        <a href="{{ route('products.index') }}">Shop</a>
        <span class="sep">›</span>
        <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a>
        <span class="sep">›</span>
        <span class="current">{{ $product->name }}</span>
    </nav>

    {{-- Main: Gallery + Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">
        {{-- LEFT: Gallery --}}
        <div class="lg:col-span-7">
            <x-product-gallery :product="$product" />
        </div>

        {{-- RIGHT: Info + Purchase --}}
        <div class="lg:col-span-5">
            <div class="gg-pdp-panel p-5 sm:p-7 lg:p-8 sticky top-[88px] lg:top-[100px]">
                <x-product-info :product="$product" />

                {{-- Purchase Section --}}
                <div class="mt-6 pt-6 border-t border-[#e6e9e2]" x-data="productCart()">
                    @if($product->stock <= 0)
                        <div class="flex items-center gap-3 p-4 bg-[#fef2f2] border border-[#fecaca] rounded-[14px]">
                            <span class="w-3 h-3 rounded-full bg-[#dc2626] animate-pulse shrink-0"></span>
                            <div>
                                <p class="text-sm font-bold text-[#b91c1c]">Out of Stock</p>
                                <p class="text-xs text-[#ef4444] mt-0.5">This product is currently unavailable.</p>
                            </div>
                        </div>
                        <div class="w-full mt-4 h-[48px] px-6 bg-[#e6e9e2] text-[#5b6259] text-sm font-bold rounded-full flex items-center justify-center gap-2 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Out of Stock
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-[#22281f]">Quantity</span>
                            <div class="gg-qty">
                                <button type="button" @click="decrement()" :disabled="qty <= 1" aria-label="Decrease quantity">−</button>
                                <input type="number" x-model.number="qty" min="1" :max="maxQty" readonly>
                                <button type="button" @click="increment()" :disabled="qty >= maxQty" aria-label="Increase quantity">+</button>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-5">
                            <button @click="addToCart({{ $product->id }})" :disabled="adding"
                                    class="gg-btn flex-1 h-[48px] text-[15px] gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                                    x-html="adding ? `<svg class='animate-spin w-5 h-5' viewBox='0 0 24 24' fill='none'><circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'/><path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z'/></svg> Adding...` : `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z'/></svg> Add to Cart`">
                            </button>
                            @auth
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST"
                                  x-data="wishlistToggle" @submit.prevent="toggle($event.target, $refs.btn)" class="shrink-0">
                                @csrf
                                <button type="submit" x-ref="btn" class="gg-pdp-icon-btn"
                                        title="{{ $product->isInWishlist(auth()->id()) ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                    <svg class="w-5 h-5 {{ $product->isInWishlist(auth()->id()) ? 'text-[#dc2626] fill-[#dc2626]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </form>
                            @endauth
                            <button type="button" onclick="shareProduct()" class="gg-pdp-icon-btn shrink-0" title="Share this product">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            </button>
                        </div>

                        <button @click="buyNow({{ $product->id }})" :disabled="buying"
                                class="gg-btn-dark w-full h-[48px] mt-3 disabled:opacity-60 disabled:cursor-not-allowed"
                                x-html="buying ? `<svg class='animate-spin w-5 h-5' viewBox='0 0 24 24' fill='none'><circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'/><path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z'/></svg> Processing...` : `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 10V3L4 14h7v7l9-11h-7z'/></svg> Buy Now`">
                        </button>

                        <div x-show="added" x-cloak x-transition
                             class="mt-4 p-3 bg-[#e4efe4] border border-[#bfd9c2] text-[#1f5c3f] rounded-[14px] text-sm font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#3f8a5c] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="addedMsg"></span>
                        </div>
                    @endif
                </div>

                {{-- Trust Badges --}}
                <div class="mt-6 pt-6 border-t border-[#e6e9e2] grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="gg-trust-item">
                        <span class="ico">🚚</span>
                        <div>
                            <p class="t">Free Shipping</p>
                            <p class="s">On all orders</p>
                        </div>
                    </div>
                    <div class="gg-trust-item">
                        <span class="ico">↩️</span>
                        <div>
                            <p class="t">Easy Returns</p>
                            <p class="s">30-day policy</p>
                        </div>
                    </div>
                    <div class="gg-trust-item">
                        <span class="ico">🔒</span>
                        <div>
                            <p class="t">Secure Checkout</p>
                            <p class="s">256-bit SSL</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs: Description / Specifications / Reviews --}}
    <section class="mt-12 sm:mt-16 scroll-fade-in" id="reviews" x-data="{ tab: 'description' }">
        <div class="gg-pdp-panel overflow-hidden">
            <div class="gg-pdp-tabs px-5 sm:px-7 border-b border-[#e6e9e2]">
                <button @click="tab = 'description'"
                        class="gg-pdp-tab" :class="tab === 'description' ? 'active' : ''">
                    Description
                </button>
                @if($specRows->count() > 0)
                <button @click="tab = 'specifications'"
                        class="gg-pdp-tab" :class="tab === 'specifications' ? 'active' : ''">
                    Specifications
                </button>
                @endif
                <button @click="tab = 'reviews'"
                        class="gg-pdp-tab" :class="tab === 'reviews' ? 'active' : ''">
                    Reviews ({{ $totalReviews }})
                </button>
            </div>

            {{-- Tab: Description --}}
            <div x-show="tab === 'description'" x-cloak class="p-5 sm:p-8">
                <div class="prose prose-sm sm:prose-base max-w-none text-[#5b6259] leading-relaxed">
                    @if($product->full_description)
                        {!! nl2br(e($product->full_description)) !!}
                    @elseif($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="text-[#5b6259] italic">No description available for this product.</p>
                    @endif
                </div>
            </div>

            {{-- Tab: Specifications --}}
            @if($specRows->count() > 0)
            <div x-show="tab === 'specifications'" x-cloak class="p-5 sm:p-8">
                <div class="divide-y divide-[#e6e9e2]">
                    @foreach($specRows as $row)
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-4 first:pt-0 last:pb-0">
                            <span class="font-bold text-[#22281f] text-sm">{{ $row[0] }}</span>
                            <span class="text-[#5b6259] text-sm sm:col-span-2">{{ $row[1] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tab: Reviews --}}
            <div x-show="tab === 'reviews'" x-cloak class="p-5 sm:p-8">
                @if($totalReviews > 0)
                <div class="flex flex-col sm:flex-row gap-8 mb-8 bg-[#f7f9f6] rounded-[14px] p-5 sm:p-6">
                    <div class="text-center shrink-0">
                        <div class="font-display text-4xl font-bold text-[#173d2b]">{{ number_format($product->avg_rating, 1) }}</div>
                        <div class="flex text-[#e0a13a] justify-center gap-0.5 mt-1.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($product->avg_rating) ? 'fill-current' : 'fill-[#e6e9e2]' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs text-[#5b6259] mt-1.5">{{ $totalReviews }} review{{ $totalReviews === 1 ? '' : 's' }}</p>
                    </div>
                    <div class="flex-1 space-y-1.5">
                        @foreach($distribution as $star => $count)
                            <div class="flex items-center gap-3 text-xs">
                                <span class="w-6 text-[#5b6259] font-semibold shrink-0">{{ $star }}★</span>
                                <div class="flex-1 h-2 rounded-full bg-[#e4efe4] overflow-hidden">
                                    <div class="h-full rounded-full bg-[#e0a13a]"
                                         style="width: {{ $totalReviews ? round($count / $totalReviews * 100) : 0 }}%"></div>
                                </div>
                                <span class="w-8 text-right text-[#5b6259] shrink-0">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @auth
                    @php $userReview = $reviews->firstWhere('user_id', auth()->id()); @endphp
                    @if(! $userReview)
                        <div class="bg-[#f7f9f6] rounded-[14px] p-5 sm:p-6 mb-8">
                            <h3 class="font-display font-bold text-[#173d2b] text-lg mb-1">Write a Review</h3>
                            <p class="text-xs text-[#5b6259] mb-4">Share your experience and help other shoppers.</p>
                            <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4" data-ajax x-data="{ submitting: false }" @submit="submitting = true">
                                @csrf
                                <div>
                                    <label class="block text-sm font-bold text-[#22281f] mb-2">Your Rating</label>
                                    <div class="flex flex-row-reverse justify-end gap-1">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer" @if($i === 5) checked @endif>
                                            <label for="star{{ $i }}" class="cursor-pointer text-[#e6e9e2] peer-checked:text-[#e0a13a] hover:text-[#e0a13a] transition-colors text-2xl">★</label>
                                        @endfor
                                    </div>
                                    @error('rating') <p class="text-[#dc2626] text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[#22281f] mb-1">Comment</label>
                                    <textarea name="comment" rows="3" class="gg-input resize-none" placeholder="Share your experience with this product..."></textarea>
                                </div>
                                <button type="submit" :disabled="submitting" class="gg-btn disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2">
                                    <svg x-show="submitting" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                    <span x-text="submitting ? 'Submitting…' : 'Submit Review'"></span>
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="bg-[#f7f9f6] rounded-[14px] p-5 sm:p-6 mb-8 text-center">
                        <p class="text-sm text-[#5b6259]">Want to share your thoughts?</p>
                        <a href="{{ route('login') }}" class="gg-btn mt-3 inline-flex">Login to write a review</a>
                    </div>
                @endauth

                <div class="space-y-4">
                    @forelse($reviews as $review)
                        <div class="bg-white rounded-[14px] border border-[#e6e9e2] p-5 transition hover:shadow-[var(--shadow-sm)]">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="gg-review-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                                    <div>
                                        <p class="font-bold text-[#22281f] text-sm">{{ $review->user->name }}</p>
                                        <p class="text-xs text-[#5b6259]">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if($review->user_id === auth()->id())
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete your review?')" data-ajax>
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-[#dc2626] hover:text-[#b91c1c] transition px-2 py-1 rounded-lg hover:bg-[#fef2f2]">Delete</button>
                                    </form>
                                @endif
                            </div>
                            <div class="flex text-[#e0a13a] mb-2 gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'fill-[#e6e9e2]' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-[#5b6259] leading-relaxed">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-5xl mb-3">🌿</div>
                            <p class="text-[#5b6259] text-sm">No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    @if($related->count() > 0)
        <section class="mt-14 sm:mt-16 scroll-fade-in">
            <div class="gg-section-head">
                <div>
                    <span class="gg-eyebrow">You May Also Like</span>
                    <h2 class="font-display font-bold">Related Products</h2>
                </div>
                <a href="{{ route('products.category', $product->category->slug) }}" class="gg-view-all">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="swiper product-swiper">
                <div class="swiper-wrapper">
                    @foreach($related as $rel)
                        <div class="swiper-slide h-full">
                            <x-home.product-card :product="$rel" />
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev dark"></div>
                <div class="swiper-button-next dark"></div>
            </div>
        </section>
    @endif

    {{-- Recently Viewed --}}
    <section class="mt-14 sm:mt-16 scroll-fade-in" x-data="recentlyViewed()">
        <div class="gg-section-head">
            <div>
                <span class="gg-eyebrow">Your Recent</span>
                <h2 class="font-display font-bold">Recently Viewed</h2>
            </div>
            <a href="{{ route('products.index') }}" class="gg-view-all">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div x-show="loading" x-cloak class="flex gap-4 overflow-hidden">
            @for($i = 0; $i < 4; $i++)
                <div class="shrink-0 w-[180px] sm:w-[220px] lg:w-[240px]">
                    <div class="gg-card animate-pulse h-full">
                        <div class="gg-media bg-[#e4efe4]"></div>
                        <div class="gg-body space-y-2.5">
                            <div class="h-3 w-3/4 rounded-full bg-[#e4efe4]"></div>
                            <div class="h-4 w-16 rounded-full bg-[#e4efe4]"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div x-show="items.length > 0 && !loading" x-cloak
             class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
            <template x-for="item in items" :key="item.id">
                <div class="shrink-0 w-[180px] sm:w-[220px] lg:w-[240px]">
                    <div class="gg-card group h-full">
                        <div class="gg-media">
                            <a :href="'/products/' + item.slug" class="block w-full h-full">
                                <img x-show="item.image"
                                     :src="'/storage/' + item.image"
                                     :alt="item.name"
                                     class="w-full h-full object-cover"
                                     loading="lazy"
                                     data-emoji-fallback="🌿">
                                <div x-show="!item.image" class="w-full h-full flex items-center justify-center text-5xl select-none">🌿</div>
                            </a>
                        </div>
                        <div class="gg-body">
                            <a :href="'/products/' + item.slug">
                                <h4 class="gg-title" x-text="item.name"></h4>
                            </a>
                            <div class="gg-price-row">
                                <span class="gg-price" x-text="'{{ getCurrencySymbol() }}' + parseFloat(item.price).toFixed({{ getCurrencySymbol() === '৳' ? 0 : 2 }})"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('recentlyViewed', () => ({
        items: [],
        loading: true,
        init() {
            const current = {{ $product->id }};
            const stored = localStorage.getItem('recentlyViewed');
            const parsed = stored ? JSON.parse(stored) : [];
            const ids = parsed.filter(id => id !== current).slice(0, 4);
            if (ids.length === 0) {
                this.loading = false;
                return;
            }
            fetch(`/api/products/recent?ids=${ids.join(',')}`)
                .then(r => r.json())
                .then(data => { this.items = data; this.loading = false; })
                .catch(() => { this.loading = false; });
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
                const res = await fetch('/cart/add/' + productId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                this.added = true;
                this.addedMsg = data.message || 'Added to cart!';
                const badge = document.getElementById('cartCount');
                if (badge && data.count) badge.textContent = data.count;
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
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
                const res = await fetch('/cart/add/' + productId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
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
