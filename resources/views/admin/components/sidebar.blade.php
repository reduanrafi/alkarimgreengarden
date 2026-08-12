<aside class="h-full flex flex-col bg-gray-950/95 backdrop-blur-2xl border-r border-white/5">
    <div class="px-6 py-6 border-b border-white/5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c1.4 2.3 2.2 4.9 2.2 7.6 0 2.5-.8 4.7-2.2 6.4-1.4-1.7-2.2-3.9-2.2-6.4C9.8 6.9 10.6 4.3 12 2z"/><path d="M12 20.5c1.4-1.7 2.2-3.9 2.2-6.4 0-2.7-.8-5.3-2.2-7.6-1.4 2.3-2.2 4.9-2.2 7.6 0 2.5.8 4.7 2.2 6.4z"/></svg>
            </div>
            <div>
                <p class="text-base font-bold text-white tracking-tight">{{ config('app.name') }}</p>
                <p class="text-[10px] text-emerald-400/70 font-medium uppercase tracking-widest">Admin Panel</p>
            </div>
        </a>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto sidebar-scroll">
        @php
            $menuItems = [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['label' => 'Categories', 'route' => 'admin.categories.index', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                ['label' => 'Products', 'route' => 'admin.products.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['label' => 'Orders', 'route' => 'admin.orders.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['label' => 'Coupons', 'route' => 'admin.coupons.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['label' => 'FAQs', 'route' => 'admin.faqs.index', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Plant Care', 'route' => 'admin.plant-care.index', 'icon' => 'M3 21v-7m0 0V5a2 2 0 012-2h4a2 2 0 012 2v9m0 0v7m0-7a5 5 0 005-5V4a1 1 0 00-1-1h-1a5 5 0 00-5 5v7m-5 0h5m6 0h5m0 0v7m-5 0v-7'],
                ['label' => 'Customers', 'route' => 'admin.customers.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Contact Messages', 'route' => 'admin.contact-messages.index', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['label' => 'Newsletter', 'route' => 'admin.newsletter.index', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                ['label' => 'Reports', 'route' => 'admin.reports.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Profile', 'route' => 'admin.profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ];
        @endphp
        @foreach($menuItems as $item)
            @php
                $routeExists = (bool) \Route::has($item['route']);
                $active = $routeExists && (request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*'));
            @endphp
            <a href="{{ $routeExists ? route($item['route']) : '#' }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group
               {{ $active ? 'bg-emerald-500/10 text-emerald-400 shadow-sm shadow-emerald-500/5' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-emerald-400' : 'text-white/30 group-hover:text-white/60' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
    <div class="px-3 py-4 border-t border-white/5">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-white/50 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200 group">
                <svg class="w-5 h-5 shrink-0 text-white/30 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
