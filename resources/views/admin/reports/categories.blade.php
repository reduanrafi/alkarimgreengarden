@extends('layouts.admin')
@section('title', 'Categories Report')
@push('styles')
<style>
.chart-placeholder { height: 260px; }
</style>
@endpush
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Reports', 'url' => route('admin.reports.index')], ['label' => 'Categories Report']]" />

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Categories Report</h1>
        <p class="text-sm text-gray-400 mt-1">Category-wise sales and product distribution</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Revenue by Category</h2>
        <div class="chart-placeholder">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Category Breakdown</h2>
        <div class="space-y-3">
            @foreach($categories as $cat)
                <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                    <div>
                        <p class="text-sm font-medium text-gray-300">{{ $cat->name }}</p>
                        <p class="text-xs text-gray-500">{{ $cat->products_count }} products &middot; {{ $cat->total_stock ?? 0 }} in stock</p>
                    </div>
                    <span class="text-sm font-semibold text-emerald-400">{{ formatPrice($cat->revenue) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
    <h2 class="text-sm font-semibold text-gray-300 mb-4">Categories Table</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-800">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">% Share</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr class="border-t border-gray-800/50 hover:bg-white/[0.02] transition">
                    <td class="px-4 py-3 text-gray-300 font-medium">{{ $cat->name }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $cat->products_count }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $cat->total_stock ?? 0 }}</td>
                    <td class="px-4 py-3 text-emerald-400 font-semibold">{{ formatPrice($cat->revenue) }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $totalRevenue > 0 ? round(($cat->revenue / $totalRevenue) * 100, 1) : 0 }}%</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;
    const textColor = 'rgba(255,255,255,0.4)';
    const labels = @json($categories->pluck('name'));
    const data = @json($categories->pluck('revenue'));
    const colors = ['#10b981','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#ec4899','#14b8a6'];

    if (document.getElementById('categoryChart')) {
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: data,
                    backgroundColor: colors.slice(0, labels.length).map(c => c + '33'),
                    borderColor: colors.slice(0, labels.length),
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: textColor, font: { size: 10 }, callback: v => '{{ getCurrencySymbol() }}' + v }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
    }
});
</script>
@endpush
