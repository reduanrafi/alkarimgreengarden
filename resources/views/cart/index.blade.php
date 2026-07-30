@extends('layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@push('styles')
<style>
input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8"
     x-data="{
        loaded: true,
        cartCount: {{ $count }},
        cartSubtotal: {{ $subtotal }},
        cartShipping: {{ $shippingCharge ?? 0 }},
        cartDiscount: {{ $discount }},
        cartGrand: {{ $grandTotal ?? 0 }},
        couponCode: '{{ session('coupon.code', '') }}',
        couponApplied: {{ session('coupon.code') ? 'true' : 'false' }},
        couponDiscount: {{ $discount ?? 0 }},
        couponMsg: '',
        couponError: '',
        get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
        async applyCoupon(form) {
            const formData = new FormData(form);
            this.couponError = '';
            this.couponMsg = '';
            try {
                const res = await fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData });
                const data = await res.json();
                if (!res.ok) { this.couponError = data.message; return; }
                this.couponApplied = true;
                this.couponCode = data.code;
                this.couponDiscount = data.discount;
                this.couponMsg = data.message;
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                setTimeout(() => this.couponMsg = '', 3000);
            } catch(e) { this.couponError = 'Failed to apply coupon.'; }
        },
        async removeCoupon() {
            const form = new FormData();
            form.append('_token', this.csrf);
            try {
                const res = await fetch('{{ route('coupon.remove') }}', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
                const data = await res.json();
                this.couponApplied = false;
                this.couponCode = '';
                this.couponDiscount = 0;
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            } catch(e) {}
        },
        async clearCart() {
            if (!confirm('Clear all items from cart?')) return;
            try {
                const form = new FormData();
                form.append('_token', this.csrf);
                form.append('_method', 'DELETE');
                const res = await fetch('{{ route('cart.clear') }}', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
                const data = await res.json();
                if (!res.ok) throw new Error();
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            } catch(e) {}
        }
     }"
     x-on:cart-updated.window="
        cartCount = $event.detail.count;
        cartSubtotal = $event.detail.subtotal;
        cartShipping = $event.detail.shipping_charge;
        cartDiscount = $event.detail.discount;
        cartGrand = $event.detail.grand_total;
     ">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 font-medium">Cart</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-serif flex items-center gap-3">
                Shopping Cart
                <template x-if="cartCount > 0">
                    <span class="text-sm font-normal text-gray-400 bg-gray-100 px-3 py-1 rounded-full" x-text="cartCount + ' item' + (cartCount !== 1 ? 's' : '')"></span>
                </template>
            </h1>
        </div>
        <template x-if="cartCount > 0">
            <button @click="clearCart" class="text-sm text-red-500 hover:text-red-600 transition flex items-center gap-1.5 px-4 py-2 rounded-xl hover:bg-red-50 border border-red-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Clear Cart
            </button>
        </template>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
            <button @click="$el.closest('div').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
            <button @click="$el.closest('div').remove()" class="ml-auto text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Coupon Messages --}}
    <template x-if="couponMsg">
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-text="couponMsg"></span>
        </div>
    </template>
    <template x-if="couponError">
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-text="couponError"></span>
            <button @click="couponError = ''" class="ml-auto text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>

    {{-- Skeleton Loading --}}
    <div x-show="!loaded" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @for($i = 0; $i < 3; $i++)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-pulse">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 skeleton rounded-xl"></div>
                        <div class="flex-1 space-y-3">
                            <div class="h-4 w-48 skeleton rounded"></div>
                            <div class="h-3 w-32 skeleton rounded"></div>
                            <div class="h-5 w-20 skeleton rounded"></div>
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
        <div x-show="cartCount > 0" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4" id="cart-items-container">
                    @foreach($items as $item)
                        <x-cart-item :item="$item" />
                    @endforeach

                    {{-- Coupon Section --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mt-4">
                        <template x-if="!couponApplied">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    Have a coupon?
                                </h3>
                                <form @submit.prevent="applyCoupon($el)" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="Enter coupon code"
                                           class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none uppercase tracking-wider">
                                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm shrink-0">Apply</button>
                                </form>
                            </div>
                        </template>
                        <template x-if="couponApplied">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Coupon Applied</p>
                                        <p class="text-xs text-gray-500">Code: <strong class="text-emerald-600" x-text="couponCode"></strong> &mdash; Saved <strong x-text="'{{ getCurrencySymbol() }}' + Number(couponDiscount).toFixed(2)"></strong></p>
                                    </div>
                                </div>
                                <button @click="removeCoupon" class="text-xs text-red-500 hover:text-red-600 transition px-3 py-1.5 rounded-lg hover:bg-red-50 border border-red-100">Remove</button>
                            </div>
                        </template>
                    </div>

                    {{-- Shipping Info --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Shipping Information
                        </h3>
                        <p class="text-xs text-gray-500 mb-3">Free shipping on orders over <strong>{{ formatPrice(100) }}</strong>. Standard shipping <strong>{{ formatPrice(9.99) }}</strong>.</p>
                        <div class="flex items-center justify-between text-sm py-2 border-t border-gray-50">
                            <span class="text-gray-600">Current subtotal</span>
                            <span class="font-medium text-gray-900" x-text="'{{ getCurrencySymbol() }}' + Number(cartSubtotal).toFixed(2)"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm py-2 border-t border-gray-50">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium" x-text="cartShipping > 0 ? '{{ getCurrencySymbol() }}' + Number(cartShipping).toFixed(2) : 'Free'" :class="cartShipping > 0 ? 'text-gray-900' : 'text-emerald-600'"></span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <x-cart-summary :subtotal="$subtotal ?? 0" :count="$count ?? 0" :shipping-charge="$shippingCharge ?? 0" :discount="$discount ?? 0" :grand-total="$grandTotal ?? 0" :tax="0" />
                </div>
            </div>
        </div>

        <div x-show="cartCount === 0" x-cloak>
            {{-- Empty Cart --}}
            <div class="text-center py-16 sm:py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 mb-6">
                    <svg class="w-14 h-14 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2 font-serif">Your cart is empty</h3>
                <p class="text-gray-400 text-sm mb-8 max-w-sm mx-auto">Looks like you haven't added any items to your cart yet. Start browsing our collection and find something you love!</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Start Shopping
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="inline-flex items-center gap-2 px-8 py-3 border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        View Wishlist
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
