@php
    $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
    $categories = \App\Models\Category::where('status', true)->get();
@endphp

<nav x-data="{ open: false, searchOpen: false, locationOpen: false, accountOpen: false, announcementVisible: true, cartCount: {{ $cartCount }} }"
     x-on:cart-updated.window="cartCount = $event.detail.count || 0"
     class="sticky top-0 z-40">
    {{-- Announcement Bar --}}
    <div x-show="announcementVisible" x-cloak class="bg-gray-900 text-white text-xs sm:text-sm py-2.5 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="text-center">Free shipping on all orders over $100 &mdash; <a href="{{ route('products.index') }}" class="text-emerald-400 hover:text-emerald-300 underline font-medium">Shop Now</a></span>
            <button @click="announcementVisible = false" class="absolute right-4 text-white/50 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-[72px] gap-3 lg:gap-6">

                {{-- Left: Logo + Location --}}
                <div class="flex items-center gap-4 lg:gap-6 shrink-0">
                    <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-2">
                        <span class="text-xl lg:text-2xl font-bold text-gray-900 tracking-tight font-serif">{{ config('app.name') }}</span>
                    </a>
                    <div class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer hover:bg-gray-50 transition relative" @click="locationOpen = !locationOpen" @click.away="locationOpen = false">
                        <svg class="w-[18px] h-[18px] text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div class="text-sm">
                            <p class="text-[11px] text-gray-400 leading-none font-medium">Deliver to</p>
                            <p class="text-xs font-semibold text-gray-800 leading-tight">Bangladesh</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <div x-show="locationOpen" x-cloak class="absolute top-full left-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 py-2 min-w-[160px] z-50">
                            <div class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Delivery</div>
                            <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-indigo-600"></span> Bangladesh</div>
                            <div class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 cursor-pointer flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-200"></span> India</div>
                            <div class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 cursor-pointer flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-200"></span> Pakistan</div>
                            <div class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 cursor-pointer flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-200"></span> Nepal</div>
                        </div>
                    </div>
                </div>

                {{-- Center: Search --}}
                <div class="hidden lg:block flex-1 max-w-[560px]" x-data="searchDropdown()" @click.away="open = false">
                    <form action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
                        <div class="relative flex-1">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="q" x-model="query" @keydown="keydown" @focus="query.length >= 2 && (open = true)"
                                   placeholder="Search products, categories, brands..."
                                   class="w-full h-[52px] pl-12 pr-4 rounded-l-[14px] border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                            <div x-show="loading" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </div>
                        </div>
                        <button type="submit" class="h-[52px] px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-r-[14px] transition flex items-center gap-2 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span class="hidden xl:inline">Search</span>
                        </button>
                        <div x-show="open && results.length > 0" x-cloak class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 max-h-96 overflow-y-auto z-50">
                            <template x-for="(item, index) in results" :key="item.id">
                                <a :href="item.url" @click="open = false"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition"
                                   :class="{'bg-indigo-50': index === selectedIndex}">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-xl shrink-0" x-html="item.image || '<span>✨</span>'"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></p>
                                        <p class="text-xs text-gray-500" x-text="item.price"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <div x-show="open && results.length === 0 && query.length >= 2 && !loading" x-cloak class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 z-50">
                            <div class="px-4 py-8 text-center">
                                <p class="text-gray-400 text-sm">No products found for "<span x-text="query"></span>"</p>
                                <a :href="'{{ route('products.index') }}?q=' + encodeURIComponent(query)" class="text-indigo-600 text-sm font-medium mt-1 inline-block hover:underline">View all results &rarr;</a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Right: Icons with Labels --}}
                <div class="flex items-center gap-0.5 sm:gap-1 lg:gap-2">

                    {{-- Search mobile trigger --}}
                    <button @click="searchOpen = !searchOpen" class="lg:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition" title="Search">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    {{-- Account Dropdown --}}
                    @auth
                        <div class="relative hidden sm:block" @click.away="accountOpen = false">
                            <button @click="accountOpen = !accountOpen" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition group">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition shrink-0">
                                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="hidden xl:block text-left leading-tight">
                                    <p class="text-[11px] text-gray-400 font-medium">Account</p>
                                    <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" :class="{'rotate-180': accountOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="accountOpen" x-cloak class="absolute right-0 top-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 py-2 min-w-[220px] z-50">
                                @if(Auth::user()->isAdmin())
                                    <div class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Admin</div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                        Dashboard
                                    </a>
                                @elseif(Auth::user()->isSeller())
                                    <div class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Seller</div>
                                    <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile
                                    </a>
                                @else
                                    <div class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">My Account</div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        My Orders
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        Wishlist
                                    </a>
                                @endif
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-1">
                            <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition group">
                                <div class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-indigo-50 transition">
                                    <svg class="h-5 w-5 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="hidden xl:block text-left leading-tight">
                                    <p class="text-[11px] text-gray-400 font-medium">Account</p>
                                    <p class="text-xs font-semibold text-gray-800">Login</p>
                                </div>
                            </a>
                            <span class="text-gray-300 text-sm hidden xl:inline">|</span>
                            <a href="{{ route('register') }}" class="hidden xl:flex items-center px-2 py-2 text-xs font-semibold text-gray-700 hover:text-indigo-600 transition">Register</a>
                        </div>
                    @endauth

                    {{-- Orders --}}
                    @auth
                    <a href="{{ route('orders.index') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-orange-50 transition">
                            <svg class="h-5 w-5 text-gray-600 group-hover:text-orange-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div class="hidden xl:block text-left leading-tight">
                            <p class="text-[11px] text-gray-400 font-medium">Orders</p>
                            <p class="text-xs font-semibold text-gray-800">Track</p>
                        </div>
                    </a>
                    @endauth

                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-red-50 transition relative">
                            <svg class="h-5 w-5 text-gray-600 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div class="hidden xl:block text-left leading-tight">
                            <p class="text-[11px] text-gray-400 font-medium">Wishlist</p>
                            <p class="text-xs font-semibold text-gray-800">Saved</p>
                        </div>
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition group relative">
                        <div class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-indigo-50 transition relative">
                            <svg class="h-5 w-5 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <template x-if="cartCount > 0">
                                <span id="cartCount" class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm ring-2 ring-white" x-text="cartCount"></span>
                            </template>
                        </div>
                        <div class="hidden xl:block text-left leading-tight">
                            <p class="text-[11px] text-gray-400 font-medium">Cart</p>
                            <p class="text-xs font-semibold text-gray-800" x-text="cartCount > 0 ? cartCount + ' items' : 'Empty'"></p>
                        </div>
                    </a>

                    {{-- Mobile Menu --}}
                    <button @click="open = !open" class="lg:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition ml-1">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Navigation --}}
    <div class="hidden lg:block bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto">
                <a href="{{ route('products.index') }}" class="shrink-0 px-4 py-3 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition whitespace-nowrap">All Products</a>
                @if($categories->count() > 0)
                    @foreach($categories as $category)
                        <a href="{{ route('products.category', $category->slug) }}"
                           class="shrink-0 px-4 py-3 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition whitespace-nowrap">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Mobile: Category Nav within slide-down --}}
    <div x-show="open" x-cloak class="lg:hidden bg-white border-b border-gray-100 shadow-lg max-h-[80vh] overflow-y-auto">
        <div class="px-4 py-3 space-y-1">
            {{-- Mobile Search --}}
            <div class="relative mb-3" x-data="{ q: '' }">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <form action="{{ route('products.index') }}" method="GET">
                    <input type="text" name="q" placeholder="Search products..."
                           class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                </form>
            </div>

            <a href="{{ route('products.index') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">All Products</a>
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}"
                       class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            @endif

            <div class="pt-3 mt-3 border-t border-gray-100 space-y-1">
                @auth
                    <div class="px-3 py-2">
                        <div class="font-medium text-sm text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">Dashboard</a>
                    @elseif(Auth::user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">Seller Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">Profile</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">Dashboard</a>
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">My Orders</a>
                        <a href="{{ route('wishlist.index') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">Wishlist</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">Profile</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition text-left">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Sign In</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition">Create Account</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Mobile Search Slide-down --}}
    <div x-show="searchOpen" x-cloak class="lg:hidden bg-white border-b border-gray-100 shadow-sm">
        <div class="px-4 py-3" x-data="{ q: '' }">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" placeholder="Search products..."
                       class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </form>
        </div>
    </div>
</nav>
