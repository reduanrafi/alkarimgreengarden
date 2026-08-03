@extends('layouts.app')

@section('title', 'Checkout — ' . config('app.name'))
@section('meta_description', 'Complete your ' . config('app.name') . ' order securely. Enter your delivery details, choose a payment method and confirm your purchase.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10"
     x-data="{
        submitting: false,
        errorMsg: '',
        submitOrder() {
            if (this.submitting) return;
            const form = document.getElementById('checkout-form');
            if (!form) return;
            const terms = form.elements.namedItem('terms');
            if (terms && !terms.checked) {
                terms.setCustomValidity('You must agree to the Terms & Conditions to continue.');
                terms.reportValidity();
                terms.setCustomValidity('');
                return;
            }
            if (!form.checkValidity()) { form.reportValidity(); return; }
            this.submitting = true;
            this.errorMsg = '';
            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), 30000);
            fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
                signal: controller.signal
            })
            .then(async res => {
                let data = {};
                try { data = await res.json(); } catch (e) {}
                if (data.redirect) { window.location.href = data.redirect; return; }
                this.submitting = false;
                if (res.status === 419) { this.errorMsg = 'Your session has expired. Please refresh the page and try again.'; return; }
                if (data.errors) { this.errorMsg = Object.values(data.errors).flat()[0] || 'Please fix the highlighted fields.'; return; }
                this.errorMsg = data.message || 'Unable to place your order. Please try again.';
            })
            .catch(err => {
                this.submitting = false;
                this.errorMsg = err && err.name === 'AbortError'
                    ? 'The request took too long. Please check your connection and try again.'
                    : 'A network error occurred. Please check your connection and try again.';
            })
            .finally(() => clearTimeout(timer));
        }
     }"
     x-on:checkout-submit.window="submitOrder()">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-ink-soft mb-2" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-brand-700 transition">Home</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('cart.index') }}" class="hover:text-brand-700 transition">Cart</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-ink font-medium">Checkout</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-display font-semibold text-ink">Checkout</h1>
        <p class="text-sm text-ink-soft mt-1">Complete your order by filling in the details below</p>
    </div>

    {{-- Flash / Validation Errors --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
            <button @click="$el.closest('div').remove()" class="ml-auto text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm mb-6 flex items-start gap-2">
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

    {{-- Checkout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-10 items-start">
        {{-- Left: Form --}}
        <div class="lg:col-span-3 space-y-6">
            <x-address-card />
            <x-checkout-form />
        </div>

        {{-- Right: Summary --}}
        <div class="lg:col-span-2">
            <div class="space-y-6 lg:sticky lg:top-24">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-xl font-semibold text-ink">Order Summary</h2>
                    <a href="{{ route('cart.index') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-900 transition">Edit Cart</a>
                </div>

                <x-order-summary :cartItems="$cartItems" :subtotal="$subtotal" :shippingCharge="$shippingCharge" :discount="$discount" :grandTotal="$grandTotal" :tax="$tax ?? 0" />

                {{-- Coupon --}}
                <div class="bg-white rounded-2xl border border-line shadow-sm p-5">
                    @if(session('coupon.code'))
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-ink truncate">{{ session('coupon.code') }}</p>
                                    <p class="text-xs text-brand-700 font-medium">You saved {{ formatPrice(session('coupon.discount')) }}</p>
                                </div>
                            </div>
                            <form action="{{ route('coupon.remove') }}" method="POST" data-ajax>
                                @csrf
                                <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 transition px-3 py-1.5 rounded-lg hover:bg-red-50 border border-red-100 shrink-0">Remove</button>
                            </form>
                        </div>
                    @else
                        <div>
                            <label class="text-xs font-semibold text-ink uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Have a coupon?
                            </label>
                            <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2" data-ajax>
                                @csrf
                                <input type="text" name="code" placeholder="Enter coupon code"
                                       class="gg-input flex-1 min-w-0 uppercase tracking-wider">
                                <button type="submit" class="gg-btn shrink-0 px-5 py-2.5 text-sm">Apply</button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- AJAX Error --}}
                <template x-if="errorMsg">
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3.5 text-sm flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p x-text="errorMsg"></p>
                    </div>
                </template>

                {{-- Place Order --}}
                <x-place-order />
            </div>
        </div>
    </div>
</div>
@endsection
