@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ submitting: false }">
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('cart.index') }}" class="hover:text-indigo-600 transition">Cart</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">Checkout</span>
    </nav>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-8 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
            <button @click="$el.closest('div').remove()" class="ml-auto text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-8 flex items-start gap-2">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="font-medium">Please fix the following errors:</p>
                <ul class="list-disc list-inside mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-10">
        <div class="lg:col-span-3">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Shipping Information</h2>
            <x-checkout-form />
        </div>

        <div class="lg:col-span-2">
            <div class="space-y-6 lg:sticky lg:top-24">
                <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
                <x-order-summary :cartItems="$cartItems" :subtotal="$subtotal" :shippingCharge="$shippingCharge" :discount="$discount" :grandTotal="$grandTotal" />

                @if(session('coupon.code'))
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>
                            <div>
                                <p class="text-sm font-medium text-emerald-800">Coupon: <strong>{{ session('coupon.code') }}</strong></p>
                                <p class="text-xs text-emerald-600">-{{ formatPrice(session('coupon.discount')) }} discount applied</p>
                            </div>
                        </div>
                        <form action="{{ route('coupon.remove') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 transition px-2 py-1 rounded-lg hover:bg-red-50">Remove</button>
                        </form>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Have a coupon?</h3>
                        <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="Enter coupon code"
                                   class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none uppercase tracking-wider">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">Apply</button>
                        </form>
                    </div>
                @endif

                <button type="submit" form="checkout-form" @click="submitting = true" :disabled="submitting"
                        class="w-full py-3.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition text-sm shadow-sm hover:shadow-md flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                    <template x-if="!submitting">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                    <template x-if="submitting">
                        <svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    </template>
                    <span x-text="submitting ? 'Processing...' : 'Place Order'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
