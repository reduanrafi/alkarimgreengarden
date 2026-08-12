@extends('layouts.admin')
@section('title', 'Sales Report')
@push('styles')
<style>
.chart-placeholder { height: 300px; }
</style>
@endpush
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Reports', 'url' => route('admin.reports.index')], ['label' => 'Sales Report']]" />

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Sales Report</h1>
        <p class="text-sm text-gray-400 mt-1">Track sales performance over time</p>
    </div>
    <form method="GET" class="flex items-center gap-2">
        <select name="period" onchange="this.form.submit()" class="bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Revenue</p>
        <p class="text-2xl font-bold text-white">{{ formatPrice($totalRevenue) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Orders</p>
        <p class="text-2xl font-bold text-white">{{ $totalOrders }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Avg Order Value</p>
        <p class="text-2xl font-bold text-white">{{ formatPrice($avgOrderValue) }}</p>
    </div>
</div>

<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-300">Sales Trend</h2>
        <span class="text-xs text-gray-500">{{ ucfirst($period) }}</span>
    </div>
    <div class="chart-placeholder">
        <canvas id="salesChart"></canvas>
    </div>
</div>

@if($salesData->count() > 0)
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
    <h2 class="text-sm font-semibold text-gray-300 mb-4">Sales Data Table</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-800">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Avg/Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesData as $row)
                <tr class="border-t border-gray-800/50 hover:bg-white/[0.02] transition">
                    <td class="px-4 py-3 text-gray-300 font-medium">{{ $row->label }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $row->count }}</td>
                    <td class="px-4 py-3 text-emerald-400 font-semibold">{{ formatPrice($row->total) }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ formatPrice($row->count > 0 ? $row->total / $row->count : 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;
    const textColor = 'rgba(255,255,255,0.4)';
    const gridColor = 'rgba(255,255,255,0.05)';
    const labels = @json($salesData->pluck('label'));
    const data = @json($salesData->pluck('total'));
    const orders = @json($salesData->pluck('count'));

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue',
                    data: data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 3,
                    yAxisID: 'y',
                },
                {
                    label: 'Orders',
                    data: orders,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: textColor, font: { size: 11 }, usePointStyle: true, pointStyle: 'circle' } }
            },
            scales: {
                x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
                y: { position: 'left', ticks: { color: textColor, font: { size: 10 }, callback: v => '{{ getCurrencySymbol() }}' + v }, grid: { color: gridColor } },
                y1: { position: 'right', ticks: { color: textColor, font: { size: 10 } }, grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
