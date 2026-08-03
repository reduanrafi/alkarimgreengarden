@php
    $user = Auth::user();
    $route = Route::currentRouteName();
    $items = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'emoji' => '🏠'],
        ['route' => 'orders.index', 'label' => 'My Orders', 'emoji' => '📦'],
        ['route' => 'account.addresses.index', 'label' => 'My Addresses', 'emoji' => '📍'],
        ['route' => 'wishlist.index', 'label' => 'Wishlist', 'emoji' => '❤️'],
        ['route' => 'profile.edit', 'label' => 'Profile Settings', 'emoji' => '👤'],
    ];
    $avatar = $user->photo
        ? asset('storage/' . $user->photo)
        : 'https://api.dicebear.com/7.x/initials/svg?seed=' . rawurlencode($user->name) . '&backgroundColor=e4efe4';
@endphp

<aside class="gg-account-side">
    <div class="gg-account-profile">
        <img src="{{ $avatar }}" alt="{{ $user->name }}" class="gg-account-avatar" loading="lazy">
        <div class="min-w-0">
            <p class="gg-account-name truncate">{{ $user->name }}</p>
            <p class="gg-account-mail truncate">{{ $user->email }}</p>
        </div>
    </div>

    <nav class="gg-account-nav">
        @foreach($items as $item)
            @if($route === $item['route'])
                <a href="{{ route($item['route']) }}" class="gg-account-link active">
                    <span class="gg-account-emoji">{{ $item['emoji'] }}</span>
                    <span>{{ $item['label'] }}</span>
                    <svg class="gg-account-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <a href="{{ route($item['route']) }}" class="gg-account-link">
                    <span class="gg-account-emoji">{{ $item['emoji'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <div class="gg-account-side-foot">
        <a href="{{ route('home') }}" class="gg-account-link">
            <span class="gg-account-emoji">🛍️</span>
            <span>Back to Shop</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="gg-account-link w-full text-left">
                <span class="gg-account-emoji">↩️</span>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>
