@props(['cartItems' => [], 'subtotal' => 0, 'shippingCharge' => 0, 'discount' => 0, 'grandTotal' => 0, 'tax' => 0])

<div class="bg-white rounded-2xl border border-line shadow-sm overflow-hidden"
     x-data="{
        subtotal: {{ $subtotal }},
        shipping: {{ $shippingCharge }},
        discount: {{ $discount }},
        tax: {{ $tax }},
        grand: {{ $grandTotal }},
        get sym() { return '{{ getCurrencySymbol() }}'; },
        fmt(n) { return this.sym + Number(n).toFixed(2); },
        setShipping(method) {
            const rates = { standard: 9.99, express: 19.99, store_pickup: 0 };
            this.shipping = this.subtotal >= 100 ? 0 : (rates[method] ?? 9.99);
            this.grand = Math.max(0, this.subtotal + this.shipping + this.tax - this.discount);
        }
     }"
     x-on:checkout-shipping.window="setShipping($event.detail)">

    <div class="px-5 sm:px-6 py-4 border-b border-line bg-cream/50 flex items-center justify-between">
        <h3 class="font-display text-lg font-semibold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Order Summary
        </h3>
        <span class="text-xs text-ink-soft bg-white border border-line px-2 py-0.5 rounded-full">{{ count($cartItems) }} item{{ count($cartItems) !== 1 ? 's' : '' }}</span>
    </div>

    <div class="px-5 sm:px-6 py-4 space-y-3 max-h-72 overflow-y-auto custom-scrollbar">
        @foreach($cartItems as $item)
            <div class="flex items-center gap-3 {{ !$loop->last ? 'pb-3 border-b border-line' : '' }}">
                <div class="w-14 h-14 rounded-xl bg-cream border border-line flex items-center justify-center text-xl shrink-0 overflow-hidden">
                    @if(!empty($item['image']))
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover fade-img relative" loading="lazy">
                    @else
                        🌿
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-ink truncate">{{ $item['name'] }}</p>
                    <p class="text-xs text-ink-soft">Qty: {{ $item['quantity'] }}</p>
                </div>
                <p class="text-sm font-semibold text-ink whitespace-nowrap">{{ formatPrice(($item['final_price'] ?? $item['price']) * $item['quantity']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="px-5 sm:px-6 py-5 border-t border-line space-y-2.5 text-sm">
        <div class="flex justify-between text-ink-soft">
            <span>Subtotal</span>
            <span class="font-medium text-ink" x-text="fmt(subtotal)"></span>
        </div>
        <div class="flex justify-between text-ink-soft" x-show="discount > 0" x-cloak>
            <span>Coupon Discount</span>
            <span class="font-medium text-brand-700" x-text="'-' + fmt(discount)"></span>
        </div>
        <div class="flex justify-between text-ink-soft">
            <span>Shipping</span>
            <span class="font-medium" x-text="shipping > 0 ? fmt(shipping) : 'Free'" :class="shipping > 0 ? 'text-ink' : 'text-brand-700'"></span>
        </div>
        <div class="flex justify-between text-ink-soft" x-show="tax > 0" x-cloak>
            <span>Estimated Tax</span>
            <span class="font-medium text-ink" x-text="fmt(tax)"></span>
        </div>
        <div class="border-t border-line pt-3 flex justify-between items-baseline">
            <span class="font-semibold text-ink">Grand Total</span>
            <span class="font-display font-semibold text-xl text-brand-700" x-text="fmt(grand)"></span>
        </div>
    </div>

    <div x-show="shipping > 0" x-cloak class="px-5 sm:px-6 pb-5">
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
    <div x-show="shipping === 0 && subtotal > 0" x-cloak class="px-5 sm:px-6 pb-5">
        <div class="bg-brand-50 border border-brand-100 rounded-xl px-4 py-3 flex items-center gap-2 text-xs text-brand-800">
            <svg class="w-4 h-4 shrink-0 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>You've unlocked <strong>Free Shipping</strong>!</span>
        </div>
    </div>
</div>
