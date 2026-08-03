@props(['subtotal' => 0, 'count' => 0, 'shippingCharge' => 0, 'discount' => 0, 'grandTotal' => 0, 'tax' => 0])

<div class="bg-white rounded-2xl border border-line shadow-sm p-6 space-y-5 sticky top-24"
     x-data="{
        subtotal: {{ $subtotal }},
        shipping: {{ $shippingCharge }},
        discount: {{ $discount }},
        tax: {{ $tax }},
        grand: {{ $grandTotal }},
        count: {{ $count }},
        couponCode: '{{ session('coupon.code', '') }}',
        couponApplied: {{ session('coupon.code') ? 'true' : 'false' }},
        couponMsg: '',
        couponError: '',
        couponLoading: false,
        couponRemoving: false,
        get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
        get sym() { return '{{ getCurrencySymbol() }}'; },
        fmt(n) { return this.sym + Number(n).toFixed(2); },
        async applyCoupon(form) {
            if (this.couponLoading) return;
            const formData = new FormData(form);
            this.couponError = '';
            this.couponMsg = '';
            this.couponLoading = true;
            try {
                const res = await fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData });
                const data = await res.json();
                if (!res.ok) { this.couponError = data.message || 'Could not apply this coupon.'; return; }
                this.couponApplied = true;
                this.couponCode = data.code;
                this.discount = data.discount;
                this.subtotal = data.subtotal;
                this.shipping = data.shipping_charge;
                this.tax = data.tax;
                this.grand = data.grand_total;
                this.count = data.count;
                this.couponMsg = data.message;
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                setTimeout(() => this.couponMsg = '', 4000);
            } catch(e) { this.couponError = window.Fashion?.friendlyError ? window.Fashion.friendlyError(e) : 'Could not apply this coupon.'; }
            finally { this.couponLoading = false; }
        },
        async removeCoupon() {
            if (this.couponRemoving) return;
            this.couponRemoving = true;
            const form = new FormData();
            form.append('_token', this.csrf);
            try {
                const res = await fetch('{{ route('coupon.remove') }}', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
                const data = await res.json();
                if (!res.ok) throw new Error();
                this.couponApplied = false;
                this.couponCode = '';
                this.discount = 0;
                this.subtotal = data.subtotal;
                this.shipping = data.shipping_charge;
                this.tax = data.tax;
                this.grand = data.grand_total;
                this.count = data.count;
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            } catch(e) { this.couponError = 'Could not remove the coupon. Please try again.'; }
            finally { this.couponRemoving = false; }
        }
     }"
     x-on:cart-updated.window="
        if ($event.detail.subtotal !== undefined) subtotal = $event.detail.subtotal;
        if ($event.detail.shipping_charge !== undefined) shipping = $event.detail.shipping_charge;
        if ($event.detail.discount !== undefined) discount = $event.detail.discount;
        if ($event.detail.tax !== undefined) tax = $event.detail.tax;
        if ($event.detail.grand_total !== undefined) grand = $event.detail.grand_total;
        if ($event.detail.count !== undefined) count = $event.detail.count;
     ">

    <div class="flex items-center justify-between">
        <h3 class="font-display text-lg font-semibold text-ink">Order Summary</h3>
        <span class="text-xs text-ink-soft bg-cream px-2.5 py-1 rounded-full border border-line" x-text="count + ' item' + (count !== 1 ? 's' : '')"></span>
    </div>

    {{-- Coupon --}}
    <div class="rounded-xl border border-line bg-cream p-4">
        @auth
        <template x-if="!couponApplied">
            <div>
                <label class="text-xs font-semibold text-ink uppercase tracking-wider flex items-center gap-1.5 mb-2">
                    <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Discount Code
                </label>
                <form @submit.prevent="applyCoupon($el)" action="{{ route('coupon.apply') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="Enter coupon code"
                           :disabled="couponLoading"
                           class="gg-input flex-1 min-w-0 uppercase tracking-wider">
                    <button type="submit" :disabled="couponLoading"
                            class="gg-btn shrink-0 px-4 py-2.5 text-sm">
                        <svg x-show="couponLoading" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        <span x-show="!couponLoading">Apply</span>
                    </button>
                </form>
            </div>
        </template>
        @else
        <div>
            <label class="text-xs font-semibold text-ink uppercase tracking-wider flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Discount Code
            </label>
            <p class="text-xs text-ink-soft leading-relaxed">
                Sign in to use coupon codes and see your savings at checkout.
                <a href="{{ route('login') }}" class="text-brand-700 font-semibold hover:underline">Sign in</a>
            </p>
        </div>
        @endauth
        <template x-if="couponApplied">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink truncate" x-text="couponCode"></p>
                        <p class="text-xs text-brand-700 font-medium" x-text="'You saved ' + fmt(discount)"></p>
                    </div>
                </div>
                <button @click="removeCoupon" :disabled="couponRemoving"
                        class="text-xs text-red-500 hover:text-red-700 transition font-medium shrink-0 inline-flex items-center gap-1 disabled:opacity-50 disabled:cursor-wait">
                    <svg x-show="couponRemoving" x-cloak class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    <span x-show="!couponRemoving">Remove</span>
                </button>
            </div>
        </template>
        <p x-show="couponMsg" x-cloak class="text-xs text-brand-700 mt-2">✓ <span x-text="couponMsg"></span></p>
        <p x-show="couponError" x-cloak class="text-xs text-red-600 mt-2"><span x-text="couponError"></span></p>
    </div>

    {{-- Totals --}}
    <div class="space-y-3 text-sm">
        <div class="flex justify-between text-ink-soft">
            <span>Subtotal</span>
            <span class="font-semibold text-ink" x-text="fmt(subtotal)"></span>
        </div>

        <div class="flex justify-between text-ink-soft" x-show="discount > 0" x-cloak>
            <span>Coupon Discount</span>
            <span class="font-semibold text-brand-700" x-text="'-' + fmt(discount)"></span>
        </div>

        <div class="flex justify-between text-ink-soft">
            <span>Shipping</span>
            <span class="font-semibold" x-text="shipping > 0 ? fmt(shipping) : 'Free'" :class="shipping > 0 ? 'text-ink' : 'text-brand-700'"></span>
        </div>

        <div class="flex justify-between text-ink-soft" x-show="tax > 0" x-cloak>
            <span>Estimated Tax</span>
            <span class="font-semibold text-ink" x-text="fmt(tax)"></span>
        </div>

        <div class="border-t border-line pt-3 flex justify-between items-baseline">
            <span class="font-semibold text-ink">Grand Total</span>
            <span class="font-display font-semibold text-xl text-brand-700" x-text="fmt(grand)"></span>
        </div>
    </div>

    {{-- Free shipping progress --}}
    <div x-show="shipping > 0" x-cloak>
        <div class="bg-brand-50 border border-brand-100 rounded-xl px-4 py-3">
            <div class="flex items-center gap-2 text-xs text-brand-800">
                <svg class="w-4 h-4 shrink-0 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                <span>Add <strong x-text="fmt(Math.max(0, 100 - subtotal))"></strong> more for <strong>Free Shipping</strong></span>
            </div>
            <div class="mt-2 w-full bg-brand-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-brand-700 h-full rounded-full transition-all duration-500" :style="'width: ' + Math.min(100, (subtotal / 100) * 100) + '%'"></div>
            </div>
        </div>
    </div>
    <div x-show="shipping === 0 && count > 0" x-cloak>
        <div class="bg-brand-50 border border-brand-100 rounded-xl px-4 py-3 flex items-center gap-2 text-xs text-brand-800">
            <svg class="w-4 h-4 shrink-0 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>You've unlocked <strong>Free Shipping</strong>!</span>
        </div>
    </div>

    {{-- Actions --}}
    <div class="space-y-3 pt-1">
        <a href="{{ route('checkout.create') }}"
           class="gg-btn w-full py-3.5 text-sm inline-flex items-center justify-center gap-2">
            Proceed to Checkout
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>

        <a href="{{ route('products.index') }}"
           class="gg-btn-outline w-full py-3 text-sm">
            Continue Shopping
        </a>
    </div>

    <div class="flex items-center justify-center gap-4 pt-1 text-[10px] text-ink-soft uppercase tracking-wider">
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Secure Checkout
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Protected
        </span>
    </div>
</div>
