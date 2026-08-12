@extends('layouts.admin')
@section('title', 'Reports')
@push('styles')
<style>
.chart-placeholder { height: 260px; }
</style>
@endpush
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Reports']]" />
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Reports & Statistics</h1>
    <p class="text-sm text-gray-400 mt-1">Sales, inventory, and performance overview</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Revenue</p>
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ formatPrice($totalRevenue) }}</p>
        <p class="text-xs text-gray-500 mt-1.5">Total collected revenue</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Orders</p>
            <div class="w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalOrders }}</p>
        <p class="text-xs text-gray-500 mt-1.5">All time orders</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Products</p>
            <div class="w-9 h-9 rounded-lg bg-purple-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalProducts }}</p>
        <p class="text-xs text-gray-500 mt-1.5">Total products in catalog</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Customers</p>
            <div class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalCustomers }}</p>
        <p class="text-xs text-gray-500 mt-1.5">Registered customers</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Revenue Overview</h2>
            <span class="text-xs text-gray-500">Monthly</span>
        </div>
        <div class="chart-placeholder flex items-center justify-center text-gray-600">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Orders by Status</h2>
            <span class="text-xs text-gray-500">Distribution</span>
        </div>
        <div class="chart-placeholder flex items-center justify-center text-gray-600">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Top Selling Products</h2>
            <a href="{{ route('admin.reports.products') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">View All</a>
        </div>
        @if($topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $product)
                    <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                        <div class="flex items-center gap-3">
                            @if($product->image)<img src="{{ asset('storage/' . $product->image) }}" class="w-9 h-9 rounded-lg object-cover">@else<div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-sm text-gray-500">P</div>@endif
                            <div><p class="text-sm font-medium text-gray-300">{{ $product->name }}</p><p class="text-xs text-gray-500">{{ $product->total_sold }} sold</p></div>
                        </div>
                        <span class="text-sm font-semibold text-emerald-400">{{ formatPrice($product->price) }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-4">No sales data yet.</p>
        @endif
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Low Stock Alert</h2>
            <a href="{{ route('admin.reports.products') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">View All</a>
        </div>
        @if($lowStockProducts->count() > 0)
            <div class="space-y-3">
                @foreach($lowStockProducts as $product)
                    <div class="flex items-center justify-between bg-red-500/10 rounded-xl px-4 py-2.5 border border-red-500/10">
                        <div><p class="text-sm font-medium text-gray-300">{{ $product->name }}</p><p class="text-xs text-gray-500">{{ $product->category->name ?? 'N/A' }}</p></div>
                        <span class="text-sm font-bold text-red-400">{{ $product->stock }} left</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-4">All products well-stocked.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;
    const textColor = 'rgba(255,255,255,0.4)';
    const gridColor = 'rgba(255,255,255,0.05)';

    const revLabels = @json($revenueByMonth->keys());
    const revData = @json($revenueByMonth->values());

    if (document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revData,
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
                    y: { ticks: { color: textColor, font: { size: 10 }, callback: v => '{{ getCurrencySymbol() }}' + Number(v).toLocaleString() }, grid: { color: gridColor } }
                }
            }
        });
    }

    const statusLabels = @json($ordersByStatus->keys());
    const statusData = @json($ordersByStatus->values());
    const statusColors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'];

    if (document.getElementById('ordersChart')) {
        new Chart(document.getElementById('ordersChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors.slice(0, statusLabels.length),
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor, font: { size: 10 }, padding: 12, usePointStyle: true, pointStyle: 'circle' }
                    }
                }
            }
        });
    }
});
</script>
@endpush
