@extends('layouts.account')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Saved Items</p>
        <h1 class="gg-title">My Wishlist ❤️</h1>
        <p class="gg-sub">Your favorite picks, saved for later.</p>
    </div>

    @if(session('success'))
        <div class="gg-alert gg-alert-success mb-6">{{ session('success') }}</div>
    @endif

    @if($wishlists->count() > 0)
        <div class="gg-wish-grid">
            @foreach($wishlists as $wishlist)
                @php $p = $wishlist->product @endphp
                <div class="gg-product-card group" x-data="{ adding: false, removing: false }">
                    <a href="{{ route('products.show', $p->slug) }}" class="gg-product-media block aspect-square bg-[#e4efe4] flex items-center justify-center p-4 overflow-hidden">
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" loading="lazy">
                        @else
                            <span class="text-5xl">{{ categoryEmoji($p->category->slug ?? null, $p->category->name ?? null) }}</span>
                        @endif
                        <form action="{{ route('wishlist.destroy', $p) }}" method="POST" @submit="removing = true"
                              class="absolute top-2.5 right-2.5">
                            @csrf @method('DELETE')
                            <button type="submit" :disabled="removing" title="Remove from wishlist"
                                    class="w-9 h-9 rounded-full bg-white shadow-sm flex items-center justify-center text-[#b91c1c] hover:bg-[#fef2f2] transition disabled:opacity-50">
                                <span x-show="removing" x-cloak class="w-4 h-4 animate-spin rounded-full border-2 border-[#b91c1c] border-t-transparent"></span>
                                <svg x-show="!removing" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </form>
                    </a>
                    <div class="p-3 sm:p-4">
                        <h3 class="font-bold text-[#173d2b] text-sm line-clamp-2">{{ $p->name }}</h3>
                        <div class="flex items-center gap-2 mt-1.5">
                            <p class="font-bold text-[#173d2b] text-sm">{{ formatPrice($p->final_price) }}</p>
                            @if($p->discount_price)
                                <p class="text-xs text-[#8a938a] line-through">{{ formatPrice($p->price) }}</p>
                            @endif
                        </div>
                        <div class="mt-3">
                            <form action="{{ route('wishlist.moveToCart', $p) }}" method="POST" @submit="adding = true">
                                @csrf
                                <button type="submit" :disabled="adding"
                                        class="gg-btn w-full !py-2 !px-3 text-xs disabled:opacity-60 disabled:cursor-wait">
                                    <span x-show="adding" x-cloak class="mr-1.5 inline-block w-3 h-3 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
                                    <span x-text="adding ? 'Adding…' : 'Add to Cart'"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $wishlists->links() }}</div>
    @else
        <x-empty-state
            icon="heart"
            title="Your wishlist is empty"
            message="Save your favorite items here and come back to them later!"
            :action="route('products.index')"
            actionText="Browse Products"
            actionIcon="plus"
        />
    @endif
@endsection
