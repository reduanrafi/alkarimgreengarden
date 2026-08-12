@extends('layouts.admin')
@section('title', 'Discounts Report')
@push('styles')
<style>
.chart-placeholder { height: 260px; }
</style>
@endpush
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Reports', 'url' => route('admin.reports.index')], ['label' => 'Discounts Report']]" />

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Discounts Report</h1>
        <p class="text-sm text-gray-400 mt-1">Coupon usage and discount analysis</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Discount Given</p>
        <p class="text-2xl font-bold text-amber-400">{{ formatPrice($totalDiscount) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Orders with Discount</p>
        <p class="text-2xl font-bold text-white">{{ $totalOrdersWithDiscount }}</p>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Avg Discount per Order</p>
        <p class="text-2xl font-bold text-white">{{ formatPrice($avgDiscount) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Monthly Discount Trend</h2>
        <div class="chart-placeholder">
            <canvas id="discountChart"></canvas>
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Coupon Performance</h2>
        @if($coupons->count() > 0)
            <div class="space-y-3">
                @foreach($coupons as $coupon)
                    <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                        <div>
                            <p class="text-sm font-medium text-gray-300">{{ $coupon->code }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : formatPrice($coupon->value) }}
                                @if($coupon->usage_limit) &middot; {{ $coupon->used_count }}/{{ $coupon->usage_limit }} used @endif
                            </p>
                        </div>
                        <span class="text-xs {{ $coupon->isValid() ? 'text-emerald-400' : 'text-red-400' }}">{{ $coupon->isValid() ? 'Active' : 'Expired' }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-4">No coupons created yet.</p>
        @endif
    </div>
</div>

<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
    <h2 class="text-sm font-semibold text-gray-300 mb-4">All Coupons</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-800">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Min Order</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Usage</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Expiry</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr class="border-t border-gray-800/50 hover:bg-white/[0.02] transition">
                    <td class="px-4 py-3 text-gray-300 font-medium">{{ $coupon->code }}</td>
                    <td class="px-4 py-3 text-gray-400 capitalize">{{ $coupon->type }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $coupon->type === 'percentage' ? $coupon->value . '%' : formatPrice($coupon->value) }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $coupon->min_order_amount ? formatPrice($coupon->min_order_amount) : 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $coupon->expiry_date?->format('M d, Y') ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $coupon->isValid() ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                            {{ $coupon->isValid() ? 'Active' : 'Expired' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No coupons found.</td></tr>
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
    const labels = @json($monthly->pluck('label'));
    const data = @json($monthly->pluck('total'));

    if (document.getElementById('discountChart')) {
        new Chart(document.getElementById('discountChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Discounts',
                    data: data,
                    backgroundColor: 'rgba(245,158,11,0.3)',
                    borderColor: '#f59e0b',
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
