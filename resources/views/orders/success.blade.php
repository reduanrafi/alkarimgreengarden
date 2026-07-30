@extends('layouts.app')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('meta_description', 'Your order has been placed successfully!')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Success Header --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 px-8 sm:px-12 py-10 sm:py-12 text-center relative overflow-hidden">
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
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm mb-6 animate-bounce">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Order Placed Successfully!</h1>
                <p class="text-emerald-100 text-sm sm:text-base max-w-md mx-auto">Thank you for your purchase! Your order has been confirmed and we'll start processing it right away.</p>
            </div>
        </div>

        {{-- Order Details --}}
        <div class="px-8 sm:px-12 py-8 sm:py-10">
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Order Number</p>
                    <p class="text-lg font-bold text-gray-900">#{{ $order->id }}</p>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Order Date</p>
                    <p class="text-lg font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Payment Method</p>
                    <p class="text-lg font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Amount</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ formatPrice($order->grand_total) }}</p>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="mt-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Shipping Address
                </h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                    <p>{{ $order->phone }}</p>
                    <p>{{ $order->email }}</p>
                    <p class="text-gray-500">{{ $order->address }}, {{ $order->district }}</p>
                    <p class="text-gray-500">{{ $order->division }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}</p>
                </div>
            </div>

            {{-- Status --}}
            <div class="mt-4 flex items-center justify-between bg-amber-50 border border-amber-100 rounded-2xl px-5 py-4 text-sm">
                <div class="flex items-center gap-2 text-amber-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>Estimated Delivery:</strong> 5-7 Business Days</span>
                </div>
                <x-order-status :status="$order->status" />
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="flex-1 py-3.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Continue Shopping
                </a>
                <a href="{{ route('orders.show', $order) }}" class="flex-1 py-3.5 border-2 border-indigo-100 text-indigo-600 font-semibold rounded-xl hover:bg-indigo-50 transition text-sm inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    View Order Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
