@extends('layouts.app')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6 animate-bounce">
            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 font-serif">Order Placed Successfully!</h1>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">Thank you for your purchase. Your order has been confirmed and we'll start processing it right away. You'll receive a confirmation email shortly.</p>

        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 sm:p-8 mb-8 text-left space-y-4 border border-gray-100">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Order Number</span>
                <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Customer</span>
                <span class="font-semibold text-gray-900">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Payment Method</span>
                <span class="font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Order Total</span>
                <span class="font-bold text-lg text-indigo-600">{{ formatPrice($order->grand_total) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Status</span>
                <x-order-status :status="$order->status" />
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Estimated Delivery</span>
                <span class="font-semibold text-gray-900">5-7 Business Days</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Shipping to</span>
                <span class="font-semibold text-gray-900 text-right max-w-[200px]">{{ $order->address }}, {{ $order->district }}</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('products.index') }}" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm inline-flex items-center gap-2 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Continue Shopping
            </a>
            <a href="{{ route('orders.show', $order) }}" class="px-8 py-3 border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition text-sm inline-flex items-center gap-2 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                View Order
            </a>
        </div>
    </div>
</div>
@endsection
