@extends('layouts.app')

@section('title', 'Order Confirmed — ' . config('app.name'))
@section('meta_description', 'Your order has been placed successfully!')

@section('content')
@php
    $shipLabel = 'Standard Delivery';
    if ($order->notes && preg_match('/Shipping Method:\s*([^\n]+)/', $order->notes, $m)) {
        $shipLabel = trim($m[1]);
    }
    $eta = str_contains($shipLabel, 'Express') ? '1-2 business days' : '5-7 business days';
    $payStatus = $order->payment_status ?? 'pending';
    $payBadge = $payStatus === 'paid' || $payStatus === 'completed'
        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
        : 'bg-amber-50 text-amber-700 border-amber-200';
    $payLabel = match ($payStatus) { 'paid' => 'Paid', 'completed' => 'Completed', 'refunded' => 'Refunded', default => 'Pending' };
@endphp
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <div class="bg-white rounded-3xl border border-line shadow-sm overflow-hidden">
        {{-- Success Header --}}
        <div class="bg-gradient-to-br from-brand-700 to-brand-900 px-8 sm:px-12 py-10 sm:py-12 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" fill="white"/>
                    <circle cx="350" cy="30" r="30" fill="white"/>
                    <circle cx="380" cy="170" r="50" fill="white"/>
                    <circle cx="20" cy="160" r="20" fill="white"/>
                    <circle cx="200" cy="190" r="25" fill="white"/>
                </svg>
            </div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-semibold text-white mb-2">Order Placed Successfully!</h1>
                <p class="text-emerald-100 text-sm sm:text-base max-w-md mx-auto">Thank you for your purchase! Your order has been confirmed and we'll start processing it right away.</p>
            </div>
        </div>

        {{-- Order Details --}}
        <div class="px-8 sm:px-12 py-8 sm:py-10">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-cream rounded-2xl p-5 border border-line">
                    <p class="text-xs text-ink-soft uppercase tracking-wider mb-1">Order Number</p>
                    <p class="text-lg font-bold text-ink">#{{ $order->invoice_no ?? $order->id }}</p>
                </div>
                <div class="bg-cream rounded-2xl p-5 border border-line">
                    <p class="text-xs text-ink-soft uppercase tracking-wider mb-1">Order Date</p>
                    <p class="text-lg font-bold text-ink">{{ $order->created_at->format('d M Y') }}</p>
                </div>
                <div class="bg-cream rounded-2xl p-5 border border-line">
                    <p class="text-xs text-ink-soft uppercase tracking-wider mb-1">Payment Method</p>
                    <p class="text-lg font-bold text-ink capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                </div>
                <div class="bg-cream rounded-2xl p-5 border border-line">
                    <p class="text-xs text-ink-soft uppercase tracking-wider mb-1">Total Amount</p>
                    <p class="text-2xl font-display font-bold text-brand-700">{{ formatPrice($order->grand_total) }}</p>
                </div>
            </div>

            {{-- Payment Status + Delivery --}}
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center justify-between bg-brand-50 border border-brand-100 rounded-2xl px-5 py-4 text-sm">
                    <div class="flex items-center gap-2 text-brand-800">
                        <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="font-medium">Payment Status</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $payBadge }}">{{ $payLabel }}</span>
                </div>
                <div class="flex items-center justify-between bg-brand-50 border border-brand-100 rounded-2xl px-5 py-4 text-sm">
                    <div class="flex items-center gap-2 text-brand-800">
                        <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium">Estimated Delivery</span>
                    </div>
                    <span class="text-xs font-semibold text-ink">{{ $eta }}</span>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="mt-4 bg-cream rounded-2xl p-5 border border-line">
                <h3 class="text-sm font-semibold text-ink mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Shipping Address
                </h3>
                <div class="text-sm text-ink-soft space-y-1">
                    <p class="font-medium text-ink">{{ $order->customer_name }}</p>
                    <p>{{ $order->phone }}</p>
                    <p>{{ $order->email }}</p>
                    <p class="text-ink-soft">{{ $order->address }}, {{ $order->district }}</p>
                    <p class="text-ink-soft">{{ $order->division }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="gg-btn flex-1 py-3.5 text-sm inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Continue Shopping
                </a>
                <a href="{{ route('orders.show', $order) }}" class="gg-btn-outline flex-1 py-3.5 text-sm inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    View Order Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
