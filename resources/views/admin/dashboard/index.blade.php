@extends('layouts.admin')
@section('title', 'Dashboard')
@push('styles')
<style>
.chart-container { position: relative; height: 280px; width: 100%; }
</style>
@endpush
@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
    <x-admin::admin-card title="Revenue" :value="formatPrice($revenue)" color="emerald" icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    <x-admin::admin-card title="Today Revenue" :value="formatPrice($todayRevenue)" color="blue" icon="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
    <x-admin::admin-card title="Total Orders" :value="$totalOrders" color="purple" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    <x-admin::admin-card title="Pending Orders" :value="$pendingOrders" color="amber" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    <x-admin::admin-card title="Completed Orders" :value="$deliveredOrders" color="emerald" icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    <x-admin::admin-card title="Cancelled Orders" :value="$cancelledOrders" color="red" icon="M6 18L18 6M6 6l12 12" />
    <x-admin::admin-card title="Total Customers" :value="$totalCustomers" color="teal" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    <x-admin::admin-card title="Low Stock" :value="$lowStockCount" color="red" icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white/80">Monthly Revenue</h2>
            <span class="text-xs text-white/30">Last 12 months</span>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white/80">Weekly Sales</h2>
            <span class="text-xs text-white/30">Last 7 days</span>
        </div>
        <div class="chart-container">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white/80">Order Status</h2>
        </div>
        <div class="chart-container" style="height: 220px;">
            <canvas id="ordersPieChart"></canvas>
        </div>
    </div>
    <div class="glass-card p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white/80">Inventory Summary</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white/5 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $totalProducts }}</p>
                <p class="text-xs text-white/40 mt-1">Products</p>
            </div>
            <div class="bg-white/5 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $totalCategories }}</p>
                <p class="text-xs text-white/40 mt-1">Categories</p>
            </div>
            <div class="bg-white/5 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $totalCustomers }}</p>
                <p class="text-xs text-white/40 mt-1">Customers</p>
            </div>
            <div class="bg-white/5 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $lowStockCount }}</p>
                <p class="text-xs text-white/40 mt-1">Low Stock</p>
            </div>
        </div>
        @if($topProducts->count() > 0)
        <div class="mt-5">
            <p class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-3">Top Selling Products</p>
            <div class="space-y-2">
                @foreach($topProducts as $product)
                <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                    <div class="flex items-center gap-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-8 h-8 rounded-lg object-cover">
                        @else
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-sm">👕</div>
                        @endif
                        <span class="text-sm text-white/80">{{ $product->name }}</span>
                    </div>
                    <span class="text-xs text-emerald-400 font-medium">{{ $product->total_sold ?? 0 }} sold</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white/80">Latest Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition">View All</a>
        </div>
        <div class="space-y-2">
            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all">
                    <div>
                        <p class="text-sm font-medium text-white/80">#{{ $order->id }} - {{ $order->customer_name }}</p>
                        <p class="text-xs text-white/30">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-emerald-400">{{ formatPrice($order->grand_total) }}</span>
                        <x-admin::status-badge :status="$order->status" type="order" />
                    </div>
                </a>
            @empty
                <p class="text-sm text-white/30 text-center py-4">No orders yet.</p>
            @endforelse
        </div>
    </div>
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white/80">Recent Customers</h2>
            <a href="{{ route('admin.customers.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition">View All</a>
        </div>
        <div class="space-y-2">
            @forelse($latestCustomers as $customer)
                <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">{{ substr($customer->name, 0, 1) }}</div>
                        <div>
                            <p class="text-sm font-medium text-white/80">{{ $customer->name }}</p>
                            <p class="text-xs text-white/30">{{ $customer->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-white/30">{{ $customer->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-sm text-white/30 text-center py-4">No customers yet.</p>
            @endforelse
        </div>
    </div>
</div>

@if($lowStockProducts->count() > 0)
<div class="glass-card p-5 mb-6">
    <h2 class="text-sm font-semibold text-white/80 mb-4">Low Stock Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($lowStockProducts as $product)
            <div class="flex items-center justify-between bg-red-500/10 rounded-xl px-4 py-3 border border-red-500/10">
                <div>
                    <p class="text-sm font-medium text-white/80">{{ $product->name }}</p>
                    <p class="text-xs text-white/40">{{ $product->category->name ?? 'N/A' }}</p>
                </div>
                <span class="text-sm font-bold text-red-400">{{ $product->stock }} left</span>
            </div>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = true;
    const textColor = 'rgba(255,255,255,0.4)';
    const gridColor = 'rgba(255,255,255,0.05)';

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Revenue',
                data: @json($monthlyRevenue),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#10b981',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
                y: { ticks: { color: textColor, font: { size: 10 }, callback: v => '{{ getCurrencySymbol() }}' + v }, grid: { color: gridColor } }
            }
        }
    });

    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: @json($weekLabels),
            datasets: [{
                label: 'Sales',
                data: @json($weeklySales),
                backgroundColor: 'rgba(16,185,129,0.3)',
                borderColor: '#10b981',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
                y: { ticks: { color: textColor, font: { size: 10 }, callback: v => '{{ getCurrencySymbol() }}' + v }, grid: { color: gridColor } }
            }
        }
    });

    new Chart(document.getElementById('ordersPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            datasets: [{
                data: [{{ $orderTotals['pending'] }}, {{ $orderTotals['processing'] }}, {{ $orderTotals['shipped'] }}, {{ $orderTotals['delivered'] }}, {{ $orderTotals['cancelled'] }}],
                backgroundColor: ['#fbbf24', '#818cf8', '#a78bfa', '#34d399', '#f87171'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: textColor, font: { size: 10 }, padding: 12 } }
            },
            cutout: '65%',
        }
    });
});
</script>
@endpush
@endsection
