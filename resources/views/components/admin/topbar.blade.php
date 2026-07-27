<header class="sticky top-0 z-40 bg-gray-950/80 backdrop-blur-xl border-b border-gray-800 px-4 lg:px-6 h-16 flex items-center justify-between shrink-0">
    {{-- Left side --}}
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="lg:hidden text-gray-400 hover:text-white transition p-1.5 rounded-lg hover:bg-white/5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="relative hidden sm:block">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search..." class="w-48 lg:w-64 bg-gray-800/50 border border-gray-700/50 rounded-lg pl-9 pr-4 py-2 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
        </div>
    </div>

    {{-- Right side --}}
    <div class="flex items-center gap-1">
        {{-- Visit site --}}
        <a href="{{ route('home') }}" target="_blank" class="p-2 text-gray-400 hover:text-emerald-400 transition rounded-lg hover:bg-white/5" title="Visit Site">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>

        {{-- Notifications --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="p-2 text-gray-400 hover:text-amber-400 transition rounded-lg hover:bg-white/5 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if($notifUnreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 flex items-center justify-center bg-amber-400 text-gray-900 text-[10px] font-bold rounded-full min-w-[18px] px-1">{{ $notifUnreadCount > 99 ? '99+' : $notifUnreadCount }}</span>
                @endif
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl py-2 z-50 max-h-[400px] overflow-y-auto">
                <div class="flex items-center justify-between px-4 py-2 border-b border-gray-800">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Notifications</p>
                    @if($notifUnreadCount > 0)
                        <a href="{{ route('admin.notifications.mark-all-read') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition">Mark all read</a>
                    @endif
                </div>
                @if($notifRecent->count() > 0)
                    @foreach($notifRecent as $n)
                        <a href="{{ route('admin.notifications.read', $n) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-white/[0.03] transition border-b border-gray-800/30 last:border-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5
                                @switch($n->type)
                                    @case('new_order') bg-emerald-500/10 text-emerald-400 @break
                                    @case('order_cancelled') bg-red-500/10 text-red-400 @break
                                    @case('low_stock') bg-amber-500/10 text-amber-400 @break
                                    @case('out_of_stock') bg-red-500/10 text-red-400 @break
                                    @case('new_customer') bg-blue-500/10 text-blue-400 @break
                                    @case('new_coupon') bg-purple-500/10 text-purple-400 @break
                                    @case('banner_updated') bg-sky-500/10 text-sky-400 @break
                                    @case('stock_in') bg-emerald-500/10 text-emerald-400 @break
                                    @case('stock_out') bg-red-500/10 text-red-400 @break
                                    @default bg-gray-500/10 text-gray-400
                                @endswitch
                            ">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @switch($n->type)
                                        @case('new_order') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /> @break
                                        @case('order_cancelled') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /> @break
                                        @case('low_stock') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /> @break
                                        @case('out_of_stock') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /> @break
                                        @case('new_customer') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /> @break
                                        @case('new_coupon') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" /> @break
                                        @case('banner_updated') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /> @break
                                        @case('stock_in') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /> @break
                                        @case('stock_out') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /> @break
                                        @default <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    @endswitch
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-200 truncate">{{ $n->title }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $n->description }}</p>
                                <p class="text-[10px] text-gray-600 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                    <a href="{{ route('admin.notifications.index') }}" class="block text-center text-xs font-medium text-emerald-400 hover:text-emerald-300 transition py-2.5 border-t border-gray-800">View all notifications</a>
                @else
                    <div class="px-4 py-8 text-center">
                        <svg class="w-8 h-8 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <p class="text-sm text-gray-500">No new notifications</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Messages --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="p-2 text-gray-400 hover:text-blue-400 transition rounded-lg hover:bg-white/5 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-72 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl py-2 z-50">
                <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Messages</p>
                <p class="px-4 py-3 text-sm text-gray-500 text-center">No new messages</p>
            </div>
        </div>

        {{-- Profile dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-300 hover:text-white transition pl-2 pr-1 py-1 rounded-lg hover:bg-white/5">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="hidden sm:inline text-sm font-medium">{{ auth()->user()->name }}</span>
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl py-2 z-50">
                <a href="{{ route('admin.profile') }}" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition">Profile</a>
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition">Settings</a>
                <hr class="my-1 border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-red-400 transition">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
