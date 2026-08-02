@extends('layouts.app')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ loaded: false, init() { setTimeout(() => this.loaded = true, 300); } }">
    <div class="mb-8">
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:text-indigo-700 transition inline-flex items-center gap-1.5 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Home
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-serif">My Wishlist</h1>
    </div>

    {{-- Skeleton Loading --}}
    <div x-show="!loaded" x-cloak class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        <x-skeletons.product-card :count="8" />
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3.5 text-sm mb-8 flex items-center gap-2 animate-slide-up" x-show="loaded">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div x-show="loaded">
    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($wishlists as $wishlist)
                @php $p = $wishlist->product @endphp
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300"
                     x-data="{ adding: false, removing: false }">
                    <a href="{{ route('products.show', $p->slug) }}" class="image-zoom block aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4 skeleton-sm">
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-contain fade-img relative" loading="lazy">
                        @else
                            <span class="text-5xl">@switch($p->category->slug ?? '') @case('mens-t-shirt') 👕 @break @case('womens-t-shirt') 👚 @break @case('bags') 👜 @break @default ✨ @endswitch</span>
                        @endif
                    </a>
                    <div class="p-3 sm:p-4">
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2">{{ $p->name }}</h3>
                        <div class="flex items-center gap-2 mt-1.5">
                            @if($p->discount_price)
                                <p class="text-indigo-600 font-bold text-sm">{{ formatPrice($p->discount_price) }}</p>
                                <p class="text-xs text-gray-400 line-through">{{ formatPrice($p->price) }}</p>
                            @else
                                <p class="text-indigo-600 font-bold text-sm">{{ formatPrice($p->final_price) }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2 mt-3">
                            <form action="{{ route('wishlist.moveToCart', $p) }}" method="POST" class="flex-1" @submit="adding = true">
                                @csrf
                                <button type="submit" :disabled="adding"
                                        class="w-full py-2 text-xs font-semibold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-sm disabled:opacity-60 disabled:cursor-wait inline-flex items-center justify-center gap-1.5">
                                    <svg x-show="adding" x-cloak class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                    <span x-text="adding ? 'Adding…' : 'Add to Cart'"></span>
                                </button>
                            </form>
                            <form action="{{ route('wishlist.destroy', $p) }}" method="POST" @submit="removing = true">
                                @csrf @method('DELETE')
                                <button type="submit" :disabled="removing" class="p-2 text-gray-400 hover:text-red-500 transition rounded-xl hover:bg-red-50 disabled:opacity-50 disabled:cursor-wait" onclick="return confirm('Remove from wishlist?')">
                                    <svg x-show="!removing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    <svg x-show="removing" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
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
    </div>
</div>
@endsection