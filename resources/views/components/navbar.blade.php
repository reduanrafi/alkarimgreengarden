@php
    $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
    $categories = $navbarCategories ?? collect();
    $cartService = app(\App\Services\CartService::class);
    $miniCartItems = $cartService->getCart();
    $miniSubtotal = $cartService->getSubtotal();
    $siteName = config('app.name');
    $siteParts = explode(' ', $siteName, 2);
@endphp

<nav x-data="{ open: false, searchOpen: false, accountOpen: false, announcementVisible: true, cartCount: {{ $cartCount }}, scrolled: false }"
     x-init="scrolled = window.scrollY > 8"
     @scroll.window.passive="scrolled = window.scrollY > 8"
      x-on:cart-updated.window="cartCount = Number($event.detail.count || 0)"
      x-on:cart-updating.window="
        if ($event.detail.count !== undefined) {
            cartCount = Number($event.detail.count);
        } else if ($event.detail.countDelta !== undefined) {
            cartCount = Math.max(0, cartCount + Number($event.detail.countDelta));
        }
      ">

    {{-- Announcement Bar --}}
    <div x-show="announcementVisible" x-cloak class="topbar">
        <p>Free shipping on all orders over $100</p>
        <button @click="announcementVisible = false" aria-label="Dismiss" class="absolute right-3 top-1/2 -translate-y-1/2 text-green-100/70 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Main Header --}}
    <div class="site-header" :class="{ 'is-scrolled': scrolled }">
        <div class="gg-container nav-row">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="logo">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c1.4 2.3 2.2 4.9 2.2 7.6 0 2.5-.8 4.7-2.2 6.4-1.4-1.7-2.2-3.9-2.2-6.4C9.8 6.9 10.6 4.3 12 2z"/><path d="M12 20.5c1.4-1.7 2.2-3.9 2.2-6.4 0-2.7-.8-5.3-2.2-7.6-1.4 2.3-2.2 4.9-2.2 7.6 0 2.5.8 4.7 2.2 6.4z"/></svg>
                {{ $siteParts[0] }}@if(isset($siteParts[1]))<span class="logo-accent">{{ $siteParts[1] }}</span>@endif
            </a>

            {{-- Desktop Nav Links --}}
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="active">Home</a>
                <a href="{{ route('products.index') }}">Shop</a>
                <a href="{{ route('products.index', ['discounted' => 1]) }}" class="sale">Sale</a>
            </nav>

            {{-- Desktop Search --}}
            <div class="hidden lg:block" x-data="searchDropdown()" @click.away="open = false">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <div class="search-box">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="q" x-model="query" @keydown="keydown" @focus="query.length >= 2 && (open = true)"
                               placeholder="Search plants, gifts, essentials..." autocomplete="off" aria-label="Search products">
                        <div x-show="loading" x-cloak>
                            <svg class="animate-spin w-4 h-4 text-[#5b6259]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </div>
                    </div>
                    <div x-show="open && results.length > 0" x-cloak class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-[#e6e9e2] max-h-96 overflow-y-auto z-50">
                        <template x-for="(item, index) in results" :key="item.id">
                            <a :href="item.url" @click="open = false"
                               class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4efe4] transition"
                               :class="{'bg-[#e4efe4]': index === selectedIndex}">
                                <div class="w-10 h-10 rounded-xl bg-[#e4efe4] flex items-center justify-center text-xl shrink-0" x-html="item.image || '<span>🌿</span>'"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-[#22281f] truncate" x-text="item.name"></p>
                                    <p class="text-xs text-[#5b6259]" x-text="item.price"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                    <div x-show="open && results.length === 0 && query.length >= 2 && !loading" x-cloak class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-[#e6e9e2] z-50">
                        <div class="px-4 py-8 text-center">
                            <p class="text-[#5b6259] text-sm">No products found for "<span x-text="query"></span>"</p>
                            <a :href="'{{ route('products.index') }}?q=' + encodeURIComponent(query)" class="text-[#1f5c3f] text-sm font-medium mt-1 inline-block hover:underline">View all results &rarr;</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Icons --}}
            <div class="nav-icons">

                <button @click="searchOpen = !searchOpen" class="icon-btn lg:hidden" title="Search" aria-label="Toggle search" :aria-expanded="searchOpen.toString()" aria-controls="mobile-search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <a href="{{ route('wishlist.index') }}" class="icon-btn" title="Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </a>

                {{-- Account --}}
                @auth
                    <div class="relative hidden sm:block" @click.away="accountOpen = false">
                        <button @click="accountOpen = !accountOpen" class="icon-btn" title="Account" aria-haspopup="true" :aria-expanded="accountOpen.toString()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </button>
                        <div x-show="accountOpen" x-cloak class="absolute right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-[#e6e9e2] py-2 min-w-[220px] z-50">
                            @if(Auth::user()->isAdmin())
                                <div class="px-4 py-2 text-xs font-medium text-[#5b6259] uppercase tracking-wider">Admin</div>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">Dashboard</a>
                            @elseif(Auth::user()->isSeller())
                                <div class="px-4 py-2 text-xs font-medium text-[#5b6259] uppercase tracking-wider">Seller</div>
                                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">Dashboard</a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">Profile</a>
                            @else
                                <div class="px-4 py-2 text-xs font-medium text-[#5b6259] uppercase tracking-wider">My Account</div>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">Dashboard</a>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">My Orders</a>
                                <a href="{{ route('account.addresses.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">My Addresses</a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">Wishlist</a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#22281f] hover:bg-[#e4efe4] transition">Profile Settings</a>
                            @endif
                            <div class="border-t border-[#e6e9e2] my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">Sign Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" title="Account">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endauth

                {{-- Cart --}}
                <button type="button" @click="$dispatch('open-mini-cart')" class="icon-btn relative" title="Cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    <template x-if="cartCount > 0">
                        <span id="cartCount" class="cart-count" x-text="cartCount"></span>
                    </template>
                </button>

                {{-- Mobile Menu Toggle --}}
                <button @click="open = !open" class="icon-btn lg:hidden" title="Menu" aria-label="Toggle menu" :aria-expanded="open.toString()" aria-controls="mobile-menu">
                    <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Category Strip (desktop) --}}
    <div class="nav-cats hidden lg:block">
        <div class="gg-container nav-cats-row">
            <a href="{{ route('products.index') }}">All Products</a>
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}">{{ categoryEmoji($category->slug, $category->name) }} {{ $category->name }}</a>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-white border-b border-[#e6e9e2] shadow-lg max-h-[80vh] overflow-y-auto">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('products.index') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">All Products</a>
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}"
                       class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">
                        {{ categoryEmoji($category->slug, $category->name) }} {{ $category->name }}
                    </a>
                @endforeach
            @endif

            <div class="pt-3 mt-3 border-t border-[#e6e9e2] space-y-1">
                @auth
                    <div class="px-3 py-2">
                        <div class="font-medium text-sm text-[#22281f]">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-[#5b6259]">{{ Auth::user()->email }}</div>
                    </div>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Dashboard</a>
                    @elseif(Auth::user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Seller Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Profile</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Dashboard</a>
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">My Orders</a>
                        <a href="{{ route('account.addresses.index') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">My Addresses</a>
                        <a href="{{ route('wishlist.index') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Wishlist</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Profile</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition text-left">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2.5 text-sm font-medium text-[#1f5c3f] hover:bg-[#e4efe4] rounded-xl transition">Sign In</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2.5 text-sm font-medium text-[#22281f] hover:bg-[#e4efe4] rounded-xl transition">Create Account</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Mobile Search --}}
    <div id="mobile-search" x-show="searchOpen" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-white border-b border-[#e6e9e2] shadow-sm">
        <div class="px-4 py-3">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[#5b6259]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" placeholder="Search plants, gifts, essentials..." aria-label="Search products"
                       class="w-full pl-9 pr-10 py-2.5 rounded-full border border-[#e6e9e2] bg-[#f7f9f6] text-sm focus:border-[#3f8a5c] outline-none transition">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-[#1f5c3f]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<x-mini-cart :items="$miniCartItems" :subtotal="$miniSubtotal" />
