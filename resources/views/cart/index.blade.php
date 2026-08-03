@extends('layouts.app')

@section('title', 'Shopping Cart — ' . config('app.name'))
@section('meta_description', 'Review the items in your ' . config('app.name') . ' cart, apply a discount code and proceed to a secure checkout.')

@push('styles')
<style>
input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10"
     x-data="{
        loaded: false,
        cartCount: {{ $count }},
        cartSubtotal: {{ $subtotal }},
        cartShipping: {{ $shippingCharge ?? 0 }},
        cartTax: {{ $tax ?? 0 }},
        cartDiscount: {{ $discount }},
        cartGrand: {{ $grandTotal ?? 0 }},
        clearing: false,
        get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
        init() { setTimeout(() => this.loaded = true, 250); },
        async clearCart() {
            if (this.clearing) return;
            if (!confirm('Clear all items from your cart?')) return;
            this.clearing = true;
            try {
                const form = new FormData();
                form.append('_token', this.csrf);
                form.append('_method', 'DELETE');
                const res = await fetch('{{ route('cart.clear') }}', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
                const data = await res.json();
                if (!res.ok) throw new Error();
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            } catch(e) { window.Fashion?.error ? window.Fashion.error('Could not clear the cart. Please try again.') : null; }
            finally { this.clearing = false; }
        }
     }"
     x-on:cart-updated.window="
        cartCount = $event.detail.count;
        cartSubtotal = $event.detail.subtotal;
        cartShipping = $event.detail.shipping_charge;
        cartTax = $event.detail.tax;
        cartDiscount = $event.detail.discount;
        cartGrand = $event.detail.grand_total;
     ">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <nav class="flex items-center gap-2 text-sm text-ink-soft mb-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-brand-700 transition">Home</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-ink font-medium">Cart</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-display font-semibold text-ink flex items-center gap-3">
                Shopping Cart
                <template x-if="cartCount > 0">
                    <span class="text-sm font-normal text-ink-soft bg-cream px-3 py-1 rounded-full border border-line" x-text="cartCount + ' item' + (cartCount !== 1 ? 's' : '')"></span>
                </template>
            </h1>
        </div>
        <template x-if="cartCount > 0">
            <button @click="clearCart" :disabled="clearing" class="gg-btn-outline text-sm px-4 py-2.5 inline-flex items-center gap-1.5 text-red-600 hover:bg-red-50 disabled:opacity-50 disabled:cursor-wait">
                <svg x-show="!clearing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <svg x-show="clearing" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                <span x-text="clearing ? 'Clearing…' : 'Clear Cart'"></span>
            </button>
        </template>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-brand-50 border border-brand-200 text-brand-800 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
            <button @click="$el.closest('div').remove()" class="ml-auto text-brand-700/70 hover:text-brand-900">
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

    {{-- Skeleton Loading --}}
    <div x-show="!loaded" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            <x-skeletons.cart-item :count="3" />
        </div>
        <div>
            <x-skeletons.checkout-summary />
        </div>
    </div>

    {{-- Cart Content --}}
    <div x-show="loaded">
        <div x-show="cartCount > 0" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2 space-y-4" id="cart-items-container">
                    @foreach($items as $item)
                        <x-cart-item :item="$item" />
                    @endforeach

                    {{-- Shipping Note --}}
                    <div class="bg-white rounded-2xl border border-line shadow-sm p-5 flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-ink">Shipping Information</p>
                            <p class="text-xs text-ink-soft mt-0.5">Free shipping on orders over <strong class="text-brand-700">{{ formatPrice(100) }}</strong>. Standard shipping <strong class="text-brand-700">{{ formatPrice(9.99) }}</strong>.</p>
                        </div>
                    </div>

                    {{-- Trust Strip --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @php
                            $perks = [
                                ['🌿', 'Eco-Friendly', 'Sustainable picks'],
                                ['🚚', 'Fast Delivery', 'Ships within 2 days'],
                                ['🛡️', 'Secure Checkout', 'SSL protected payment'],
                            ];
                        @endphp
                        @foreach($perks as $perk)
                            <div class="bg-cream border border-line rounded-2xl px-4 py-3 text-center">
                                <div class="text-2xl">{{ $perk[0] }}</div>
                                <p class="text-xs font-semibold text-ink mt-1">{{ $perk[1] }}</p>
                                <p class="text-[11px] text-ink-soft">{{ $perk[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <x-cart-summary :subtotal="$subtotal ?? 0" :count="$count ?? 0" :shipping-charge="$shippingCharge ?? 0" :discount="$discount ?? 0" :grand-total="$grandTotal ?? 0" :tax="$tax ?? 0" />
                </div>
            </div>
        </div>

        {{-- Empty Cart --}}
        <div x-show="cartCount === 0" x-cloak>
            <div class="bg-white border border-line rounded-3xl shadow-sm px-6 py-16 sm:py-20 text-center max-w-2xl mx-auto">
                <div class="w-20 h-20 mx-auto rounded-full bg-brand-100 flex items-center justify-center text-4xl select-none">🛒</div>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink mt-6">Your cart is empty</h2>
                <p class="text-ink-soft text-sm sm:text-base mt-3 max-w-md mx-auto">
                    Looks like you haven't added anything yet. Browse our collection of plants and lifestyle goods and find something you'll love.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                    <a href="{{ route('products.index') }}"
                       class="gg-btn px-8 py-3 inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Start Shopping
                    </a>
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="gg-btn-outline px-8 py-3 inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            View Wishlist
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
