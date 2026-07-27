@extends('layouts.app')

@section('title', 'Cart - ' . config('app.name'))

@push('styles')
<style>
input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }
</style>
@endpush

@section('content')
<div x-data="{ loaded: true }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:text-indigo-700 transition inline-flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Home
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-serif">Shopping Cart</h1>
        </div>

        @if(count($cartItems) > 0)
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear all items from cart?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-500 hover:text-red-600 transition flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Clear Cart
                </button>
            </form>
        @endif
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3.5 text-sm mb-8 flex items-center gap-2 animate-slide-up">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-8 flex items-center gap-2 animate-slide-up">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Skeleton Loading --}}
    <div x-show="!loaded" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @for($i = 0; $i < 3; $i++)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-pulse">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 skeleton rounded-xl"></div>
                        <div class="flex-1 space-y-3">
                            <div class="h-4 w-48 skeleton rounded"></div>
                            <div class="h-3 w-24 skeleton rounded"></div>
                            <div class="h-5 w-16 skeleton rounded"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex gap-2">
                                <div class="w-9 h-9 skeleton rounded-xl"></div>
                                <div class="w-14 h-9 skeleton rounded-xl"></div>
                                <div class="w-9 h-9 skeleton rounded-xl"></div>
                            </div>
                        </div>
                        <div class="space-y-2 text-right">
                            <div class="h-5 w-20 skeleton rounded"></div>
                            <div class="h-3 w-16 skeleton rounded ml-auto"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4 animate-pulse">
                <div class="h-5 w-32 skeleton rounded"></div>
                <div class="space-y-3">
                    <div class="flex justify-between"><div class="h-4 w-20 skeleton rounded"></div><div class="h-4 w-16 skeleton rounded"></div></div>
                    <div class="flex justify-between"><div class="h-4 w-20 skeleton rounded"></div><div class="h-4 w-16 skeleton rounded"></div></div>
                    <div class="flex justify-between"><div class="h-4 w-20 skeleton rounded"></div><div class="h-4 w-16 skeleton rounded"></div></div>
                    <div class="border-t pt-3 flex justify-between"><div class="h-5 w-24 skeleton rounded"></div><div class="h-5 w-20 skeleton rounded"></div></div>
                </div>
                <div class="h-12 skeleton rounded-xl"></div>
                <div class="h-12 skeleton rounded-xl"></div>
            </div>
        </div>
    </div>

    {{-- Cart Content --}}
    <div x-show="loaded">
        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <x-cart-item :item="$item" />
                    @endforeach

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Have a coupon?</h3>
                        <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="Enter coupon code"
                                   class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none uppercase tracking-wider">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">Apply</button>
                        </form>
                        @if(session('coupon.code'))
                            <div class="mt-3 flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2.5">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>
                                    <span class="text-emerald-800">Code: <strong>{{ session('coupon.code') }}</strong> (-{{ formatPrice(session('coupon.discount')) }})</span>
                                </div>
                                <form action="{{ route('coupon.remove') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-600 transition px-2 py-1 rounded-lg hover:bg-red-50">Remove</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Shipping Estimate</h3>
                        <p class="text-xs text-gray-500 mb-2">Free shipping on orders over $100. Standard shipping $9.99.</p>
                        @php $subtotal = $total; $shippingCharge = $subtotal >= 100 ? 0 : 9.99; @endphp
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Current subtotal</span>
                            <span class="font-medium text-gray-900">{{ formatPrice($subtotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm mt-1">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium {{ $shippingCharge > 0 ? 'text-gray-900' : 'text-emerald-600' }}">{{ $shippingCharge > 0 ? formatPrice($shippingCharge) : 'Free' }}</span>
                        </div>
                        @if($shippingCharge > 0)
                            <div class="mt-2 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 text-xs text-amber-700">
                                Add {{ formatPrice(100 - $subtotal) }} more for free shipping!
                            </div>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <x-cart-summary :total="$total" :count="$count" />
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-6">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 font-serif">Your cart is empty</h3>
                <p class="text-gray-400 text-sm mb-8 max-w-sm mx-auto">Looks like you haven't added any items to your cart yet. Start browsing our collection!</p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
