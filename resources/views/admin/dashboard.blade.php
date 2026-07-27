@extends('layouts.admin')
@section('title', 'Dashboard')
@push('styles')
<style>
.chart-placeholder { height: 260px; }
</style>
@endpush
@section('content')
{{-- Welcome --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-gray-400 mt-1">Here's what's happening with your store today.</p>
    </div>
    <span class="hidden sm:inline text-sm text-gray-500">{{ now()->format('l, F d, Y') }}</span>
</div>

{{-- Inventory Stats Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Stock</p>
        <p class="text-xl font-bold text-white">{{ number_format($totalStockQty) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Low Stock</p>
        <p class="text-xl font-bold text-amber-400">{{ $lowStockCount }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Out of Stock</p>
        <p class="text-xl font-bold text-red-400">{{ number_format($outOfStockCount) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Stock Value</p>
        <p class="text-xl font-bold text-white">{{ $currencySymbol }}{{ number_format($stockValue, 2) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Today Stock In</p>
        <p class="text-xl font-bold text-emerald-400">{{ number_format($todayStockIn) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Today Stock Out</p>
        <p class="text-xl font-bold text-red-400">{{ number_format($todayStockOut) }}</p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    {{-- Total Products --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Products</p>
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalProducts) }}</p>
    </div>

    {{-- Total Categories --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</p>
            <div class="w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalCategories) }}</p>
    </div>

    {{-- Total Customers --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Customers</p>
            <div class="w-9 h-9 rounded-lg bg-purple-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalCustomers) }}</p>
    </div>

    {{-- Total Orders --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Orders</p>
            <div class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($totalOrders) }}</p>
    </div>

    {{-- Total Revenue --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Revenue</p>
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $currencySymbol }}{{ number_format($revenue, 2) }}</p>
    </div>

    {{-- Low Stock --}}
    <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition block">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Low Stock</p>
            <div class="w-9 h-9 rounded-lg bg-red-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $lowStockCount }}</p>
    </a>
</div>

{{-- Low Stock Notification Banner --}}
@if($lowStockCount > 0)
<div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6 flex items-center gap-3">
    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm text-red-300">
        <strong class="text-red-200">{{ $lowStockCount }} product(s)</strong> are running low on stock.
        <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="text-red-400 hover:text-red-300 underline ml-1">Review now</a>
    </p>
</div>
@endif

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Revenue Overview</h2>
            <span class="text-xs text-gray-500">Last 12 months</span>
        </div>
        <div class="chart-placeholder flex items-center justify-center text-gray-600">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Orders Overview</h2>
            <span class="text-xs text-gray-500">Last 7 days</span>
        </div>
        <div class="chart-placeholder flex items-center justify-center text-gray-600">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>

{{-- Recent Orders Table --}}
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-300">Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Date</th>
                    <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-500/10 text-amber-400',
                        'confirmed' => 'bg-blue-500/10 text-blue-400',
                        'processing' => 'bg-blue-500/10 text-blue-400',
                        'packed' => 'bg-purple-500/10 text-purple-400',
                        'shipped' => 'bg-purple-500/10 text-purple-400',
                        'delivered' => 'bg-emerald-500/10 text-emerald-400',
                        'completed' => 'bg-emerald-500/10 text-emerald-400',
                        'cancelled' => 'bg-red-500/10 text-red-400',
                        'returned' => 'bg-red-500/10 text-red-400',
                        'refunded' => 'bg-red-500/10 text-red-400',
                    ];
                @endphp
                @forelse($recentOrders as $order)
                <tr class="border-t border-gray-800/50 hover:bg-white/[0.02] transition">
                    <td class="px-3 py-3 font-medium text-gray-300">#{{ $order->invoice_no ?? $order->id }}</td>
                    <td class="px-3 py-3 text-gray-400">{{ $order->customer_name ?? $order->user?->name ?? 'Guest' }}</td>
                    <td class="px-3 py-3">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-500/10 text-gray-400' }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="px-3 py-3 text-gray-300">{{ $currencySymbol }}{{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-3 py-3 text-gray-500 hidden lg:table-cell">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-3 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">
                            View
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="border-t border-gray-800/50">
                    <td colspan="6" class="px-3 py-6 text-center text-gray-500">No orders yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Products + Low Stock --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    {{-- Recent Products --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Recent Products</h2>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">View All</a>
        </div>
        <div class="space-y-2">
            @forelse($latestProducts as $product)
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/[0.02] transition">
                <div class="w-9 h-9 rounded-lg bg-gray-800/50 border border-gray-700/50 overflow-hidden flex-shrink-0">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium text-gray-300 hover:text-emerald-400 transition truncate block">{{ $product->name }}</a>
                    <p class="text-xs text-gray-500 truncate">{{ $product->category?->name ?? 'Uncategorized' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-medium text-gray-300">{{ $currencySymbol }}{{ number_format($product->final_price ?? $product->price, 2) }}</p>
                    <p class="text-xs {{ $product->is_low_stock || $product->stock <= 0 ? 'text-red-400' : 'text-gray-500' }}">{{ $product->stock }} in stock</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No products yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Low Stock Products</h2>
            <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-800/30">
                        <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alert</th>
                        <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/20">
                    @forelse($lowStockProducts as $product)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="px-2 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gray-800/50 border border-gray-700/50 overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium text-gray-300 hover:text-emerald-400 transition truncate max-w-[160px] block">{{ $product->name }}</a>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 text-xs text-gray-500">{{ $product->sku ?? '—' }}</td>
                        <td class="px-2 py-2.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400">{{ $product->stock }}</span>
                        </td>
                        <td class="px-2 py-2.5 text-xs text-gray-500">{{ $product->low_stock_alert_quantity ?? 5 }}</td>
                        <td class="px-2 py-2.5">
                            @if($product->status)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-2 py-6 text-center text-sm text-gray-500">All products are well-stocked.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Notifications --}}
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-300">Recent Notifications</h2>
        <a href="{{ route('admin.notifications.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">View All</a>
    </div>
    <div class="space-y-1">
        @forelse($recentNotifications as $n)
        <div class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white/[0.02] transition {{ !$n->is_read ? 'border-l-2 border-emerald-400/50 pl-[9px]' : 'pl-3' }}">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
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
                <p class="text-sm text-gray-200 truncate">{{ $n->title }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $n->description }}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-[10px] text-gray-600">{{ $n->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-500 text-center py-4">No notifications yet.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart !== 'undefined') {
        const textColor = 'rgba(255,255,255,0.4)';
        const gridColor = 'rgba(255,255,255,0.05)';
        const currencySymbol = '{{ $currencySymbol }}';

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Revenue',
                    data: @json($monthlyRevenue),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#10b981',
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, font: { size: 10 }, callback: v => currencySymbol + Number(v).toLocaleString() }, grid: { color: gridColor } }
                }
            }
        });

        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: @json($weekLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($weeklySales),
                    backgroundColor: 'rgba(16,185,129,0.3)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, font: { size: 10 }, callback: v => currencySymbol + Number(v).toLocaleString() }, grid: { color: gridColor } }
                }
            }
        });
    }
});
</script>
@endpush