@php
    $user = Auth::user();
    $ordersCount = $user->orders()->count();
    $wishlistCount = $user->wishlists()->count();
    $totalSpent = $user->totalSpent();
    $recentOrders = $user->orders()->with('items.product')->latest()->take(5)->get();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight font-serif">Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">Welcome back, {{ $user->name }}!</p>
            </div>
            <a href="{{ route('products.index') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-[#1f5c3f] text-white text-sm font-semibold rounded-xl hover:bg-[#173d2b] transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Start Shopping
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#e4efe4] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-[#1f5c3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $ordersCount }}</p>
                            <p class="text-xs text-gray-500">Total Orders</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalSpent > 0 ? config('app.currency', '$') . number_format($totalSpent, 2) : '$0.00' }}</p>
                            <p class="text-xs text-gray-500">Total Spent</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $wishlistCount }}</p>
                            <p class="text-xs text-gray-500">Wishlist Items</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->created_at->diffInDays(now()) }}</p>
                            <p class="text-xs text-gray-500">Days with us</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Links + Profile --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('orders.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-[#e4efe4] flex items-center justify-center mb-4 group-hover:bg-[#d5e6d5] transition-colors">
                        <svg class="w-6 h-6 text-[#1f5c3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-[#1f5c3f] transition-colors">My Orders</h3>
                    <p class="text-sm text-gray-500 mt-1">View and track your orders</p>
                </a>

                <a href="{{ route('wishlist.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mb-4 group-hover:bg-red-100 transition-colors">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Wishlist</h3>
                    <p class="text-sm text-gray-500 mt-1">View your saved items</p>
                </a>

                <a href="{{ route('profile.edit') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Profile</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage your account settings</p>
                </a>
            </div>

            {{-- Profile Card + Recent Orders --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Profile Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#e4efe4] to-[#d5e6d5] text-[#1f5c3f] flex items-center justify-center text-2xl font-bold shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg truncate">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Profile
                        </a>
                        <a href="{{ route('password.confirm') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Change Password
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Order History
                        </a>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="font-semibold text-gray-900">Recent Orders</h3>
                            <a href="{{ route('orders.index') }}" class="text-sm text-[#1f5c3f] hover:text-[#173d2b] font-medium">View All</a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentOrders as $order)
                                    <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition group">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#e4efe4] to-[#d5e6d5] text-[#1f5c3f] flex items-center justify-center shrink-0 group-hover:from-[#e4efe4] group-hover:to-[#d5e6d5] transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">Order #{{ $order->id }}</p>
                                            <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }} Â· {{ $order->items->count() }} item(s)</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-sm font-semibold text-gray-900">{{ formatPrice($order->grand_total) }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                @if($order->status === 'delivered' || $order->status === 'completed') bg-emerald-50 text-emerald-700
                                                @elseif($order->status === 'cancelled') bg-red-50 text-red-700
                                                @elseif($order->status === 'shipped') bg-[#e4efe4] text-[#1f5c3f]
                                                @elseif($order->status === 'processing') bg-[#e4efe4] text-[#1f5c3f]
                                                @else bg-amber-50 text-amber-700 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-gray-500 text-sm mb-4">No orders yet</p>
                                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1f5c3f] text-white text-sm font-semibold rounded-xl hover:bg-[#173d2b] transition shadow-sm">
                                    Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
