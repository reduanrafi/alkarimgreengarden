@extends('layouts.admin')
@section('title', 'Payments Report')
@push('styles')
<style>
.chart-placeholder { height: 260px; }
</style>
@endpush
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Reports', 'url' => route('admin.reports.index')], ['label' => 'Payments Report']]" />

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Payments Report</h1>
        <p class="text-sm text-gray-400 mt-1">Payment methods, statuses, and collection trends</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Collected</p>
        <p class="text-2xl font-bold text-emerald-400">{{ formatPrice($totalCollected) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pending</p>
        <p class="text-2xl font-bold text-amber-400">{{ formatPrice($totalPending) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Failed</p>
        <p class="text-2xl font-bold text-red-400">{{ formatPrice($totalFailed) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Payment Methods</h2>
        <div class="chart-placeholder">
            <canvas id="methodChart"></canvas>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Payment Status</h2>
        <div class="chart-placeholder">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Monthly Collection</h2>
        <div class="chart-placeholder">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Payment Methods Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-800">
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($byMethod as $method)
                    <tr class="border-t border-gray-800/50 hover:bg-white/[0.02] transition">
                        <td class="px-4 py-3 text-gray-300 font-medium capitalize">{{ str_replace('_', ' ', $method->payment_method) }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $method->count }}</td>
                        <td class="px-4 py-3 text-emerald-400 font-semibold">{{ formatPrice($method->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Payment Status Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-800">
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($byStatus as $ps)
                    <tr class="border-t border-gray-800/50 hover:bg-white/[0.02] transition">
                        <td class="px-4 py-3 text-gray-300 font-medium capitalize">{{ $ps->payment_status }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $ps->count }}</td>
                        <td class="px-4 py-3 text-emerald-400 font-semibold">{{ formatPrice($ps->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;
    const textColor = 'rgba(255,255,255,0.4)';
    const colors = ['#10b981','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#ec4899'];

    const methodLabels = @json($byMethod->pluck('payment_method'));
    const methodData = @json($byMethod->pluck('total'));

    if (document.getElementById('methodChart')) {
        new Chart(document.getElementById('methodChart'), {
            type: 'doughnut',
            data: {
                labels: methodLabels,
                datasets: [{ data: methodData, backgroundColor: colors.slice(0, methodLabels.length), borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { color: textColor, font: { size: 10 }, padding: 12, usePointStyle: true, pointStyle: 'circle' } } }
            }
        });
    }

    const statusLabels = @json($byStatus->pluck('payment_status'));
    const statusData = @json($byStatus->pluck('total'));

    if (document.getElementById('statusChart')) {
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{ data: statusData, backgroundColor: colors.slice(0, statusLabels.length), borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { color: textColor, font: { size: 10 }, padding: 12, usePointStyle: true, pointStyle: 'circle' } } }
            }
        });
    }

    const monthLabels = @json($monthly->pluck('label'));
    const monthData = @json($monthly->pluck('total'));

    if (document.getElementById('monthlyChart')) {
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Collected',
                    data: monthData,
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
                    x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: textColor, font: { size: 10 }, callback: v => '{{ getCurrencySymbol() }}' + v }, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
    }
});
</script>
@endpush
