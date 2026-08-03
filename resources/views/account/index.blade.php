@extends('layouts.account')

@section('title', 'My Account - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">My Account</p>
        <h1 class="gg-title">Welcome back, {{ auth()->user()->first_name ?: explode(' ', auth()->user()->name)[0] }} 🌿</h1>
        <p class="gg-sub">Here's a snapshot of your garden. Everything you need is one tap away.</p>
    </div>

    @if(session('status'))
        <div class="gg-alert gg-alert-success mb-6">{{ session('status') }}</div>
    @endif

    <div class="gg-dash-grid">
        <x-dashboard-card label="Total Orders" :value="$ordersCount" emoji="📦" :href="route('orders.index')" />
        <x-dashboard-card label="Wishlist Items" :value="$wishlistCount" emoji="❤️" :href="route('wishlist.index')" />
        <x-dashboard-card label="Saved Addresses" :value="$addressesCount" emoji="📍" :href="route('account.addresses.index')" />
        <x-dashboard-card label="Total Spent" value="{{ $totalSpent ?: formatPrice(0) }}" emoji="💰" :href="route('orders.index')" />
    </div>

    <div class="mt-10">
        <div class="gg-section-head gg-section-head-sm">
            <div>
                <h2 class="gg-title">Recent Orders</h2>
                <p class="gg-sub">Your latest activity at a glance.</p>
            </div>
            <a href="{{ route('orders.index') }}" class="gg-view-all">View all →</a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                    <x-order-card :order="$order" />
                @endforeach
            </div>
        @else
            <x-empty-state
                icon="orders"
                title="No orders yet"
                message="You haven't placed any orders yet. Start shopping and your orders will appear here."
                :action="route('products.index')"
                actionText="Start Shopping"
            />
        @endif
    </div>

    <div class="mt-10">
        <div class="gg-section-head gg-section-head-sm">
            <div>
                <h2 class="gg-title">Quick Links</h2>
            </div>
        </div>
        <div class="gg-quick-grid">
            <a href="{{ route('products.index') }}" class="gg-quick-link">🛍️ <span>Shop All Products</span></a>
            <a href="{{ route('wishlist.index') }}" class="gg-quick-link">❤️ <span>My Wishlist</span></a>
            <a href="{{ route('account.addresses.create') }}" class="gg-quick-link">📍 <span>Add New Address</span></a>
            <a href="{{ route('profile.edit') }}" class="gg-quick-link">👤 <span>Profile Settings</span></a>
        </div>
    </div>
@endsection
