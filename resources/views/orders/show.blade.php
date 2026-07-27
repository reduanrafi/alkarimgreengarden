@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('orders.index') }}" class="hover:text-indigo-600 transition">My Orders</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">Order #{{ $order->id }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-gray-900">Order #{{ $order->id }}</h2>
                    <x-order-status :status="$order->status" />
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wider">Ordered</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $order->ordered_at ? $order->ordered_at->format('d M Y, h:i A') : $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wider">Payment</span>
                        <p class="font-medium text-gray-900 mt-0.5 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wider">Payment Status</span>
                        <p class="font-medium mt-0.5 capitalize text-{{ $order->payment_status === 'paid' ? 'emerald' : 'amber' }}-600">{{ $order->payment_status }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wider">Total</span>
                        <p class="font-bold text-indigo-600 mt-0.5">{{ formatPrice($order->grand_total) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-lg text-gray-900 mb-6">Status Timeline</h2>
                @php
                    $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                    $currentIndex = array_search($order->status, $statuses);
                @endphp
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                    <div class="space-y-6 relative">
                        @foreach($statuses as $i => $s)
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium relative z-10 shrink-0
                                    {{ $i <= $currentIndex ? 'bg-gradient-to-br from-[#66D9F1] to-[#4CC9F0] text-white shadow-sm' : 'bg-gray-100 text-gray-400' }}">
                                    <template x-if="{{ $i < $currentIndex }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                    <template x-if="{{ $i == $currentIndex }}">
                                        <span>{{ $i + 1 }}</span>
                                    </template>
                                    <template x-if="{{ $i > $currentIndex }}">
                                        <span>{{ $i + 1 }}</span>
                                    </template>
                                </div>
                                <div>
                                    <p class="text-sm font-medium {{ $i <= $currentIndex ? 'text-gray-900' : 'text-gray-400' }}">{{ ucfirst($s) }}</p>
                                    @if($i == $currentIndex && $order->ordered_at)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->ordered_at->format('d M Y, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-lg text-gray-900 mb-4">Order Items</h2>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-4 {{ $loop->first ? 'pt-0' : '' }} {{ $loop->last ? 'pb-0' : '' }}">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-2xl shrink-0 overflow-hidden">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <span>📦</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm">{{ $item->product->name ?? 'Product' }}</p>
                                <div class="flex items-center gap-3 mt-0.5 text-xs text-gray-500">
                                    <span>Qty: {{ $item->quantity }}</span>
                                    <span>{{ formatPrice($item->price) }} each</span>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-sm">{{ formatPrice($item->price * $item->quantity) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 pt-4 mt-2 space-y-1.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-900">{{ formatPrice($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Shipping</span>
                        <span class="font-medium">{{ $order->shipping_charge > 0 ? formatPrice($order->shipping_charge) : 'Free' }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>Discount</span>
                            <span class="font-medium">-{{ formatPrice($order->discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base border-t border-gray-100 pt-3 mt-3">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-indigo-600">{{ formatPrice($order->grand_total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h2 class="font-bold text-lg text-gray-900">Shipping Address</h2>
                </div>
                <div class="text-sm space-y-1.5 text-gray-700">
                    <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                    <p class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $order->phone }}
                    </p>
                    <p class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $order->email }}
                    </p>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->upazila ? $order->upazila . ', ' : '' }}{{ $order->district }}</p>
                        <p>{{ $order->division }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}</p>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-sm text-gray-900 mb-2">Order Notes</h2>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif

            <a href="{{ route('orders.index') }}" class="block w-full py-3 text-center border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition text-sm">
                <span class="flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Orders
                </span>
            </a>
        </div>
    </div>
</div>
@endsection
