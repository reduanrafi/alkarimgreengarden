@props(['subtotal' => 0, 'count' => 0, 'shippingCharge' => 0, 'discount' => 0, 'grandTotal' => 0, 'tax' => 0])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5 sticky top-24" x-data="{
    subtotal: {{ $subtotal }},
    shipping: {{ $shippingCharge }},
    discount: {{ $discount }},
    tax: {{ $tax }},
    grand: {{ $grandTotal }},
    count: {{ $count }}
}" x-on:cart-updated.window="
    if ($event.detail.subtotal !== undefined) subtotal = $event.detail.subtotal;
    if ($event.detail.shipping_charge !== undefined) shipping = $event.detail.shipping_charge;
    if ($event.detail.discount !== undefined) discount = $event.detail.discount;
    if ($event.detail.grand_total !== undefined) grand = $event.detail.grand_total;
    if ($event.detail.count !== undefined) count = $event.detail.count;
">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg text-gray-900">Cart Summary</h3>
        <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full" x-text="count + ' item' + (count !== 1 ? 's' : '')"></span>
    </div>

    <div class="space-y-3 text-sm">
        <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span class="font-medium text-gray-900" x-text="'{{ getCurrencySymbol() }}' + Number(subtotal).toFixed(2)"></span>
        </div>

        <div class="flex justify-between text-gray-600">
            <span>Shipping</span>
            <span class="font-medium" x-text="shipping > 0 ? '{{ getCurrencySymbol() }}' + Number(shipping).toFixed(2) : 'Free'" :class="shipping > 0 ? 'text-gray-900' : 'text-emerald-600'"></span>
        </div>

        <div class="flex justify-between text-gray-600" x-show="tax > 0" x-cloak>
            <span>Estimated Tax</span>
            <span class="font-medium text-gray-900" x-text="'{{ getCurrencySymbol() }}' + Number(tax).toFixed(2)"></span>
        </div>

        <div class="flex justify-between text-gray-600" x-show="discount > 0" x-cloak>
            <span>Coupon Discount</span>
            <span class="font-medium text-emerald-600" x-text="'-{{ getCurrencySymbol() }}' + Number(discount).toFixed(2)"></span>
        </div>

        <div class="border-t border-gray-100 pt-3 flex justify-between text-base">
            <span class="font-semibold text-gray-900">Grand Total</span>
            <span class="font-bold text-lg text-indigo-600" x-text="'{{ getCurrencySymbol() }}' + Number(grand).toFixed(2)"></span>
        </div>
    </div>

    <div x-show="shipping > 0" x-cloak>
        <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3">
            <div class="flex items-center gap-2 text-xs text-amber-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Add <strong x-text="'{{ getCurrencySymbol() }}' + Number(Math.max(0, 100 - subtotal)).toFixed(2)"></strong> more for <strong>Free Shipping</strong></span>
            </div>
            <div class="mt-2 w-full bg-amber-200/50 rounded-full h-1.5 overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full transition-all duration-500" :style="'width: ' + Math.min(100, (subtotal / 100) * 100) + '%'"></div>
            </div>
        </div>
    </div>
    <div x-show="shipping === 0 && count > 0" x-cloak>
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 flex items-center gap-2 text-xs text-emerald-700">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Free Shipping applied!</span>
        </div>
    </div>

    <div class="space-y-3 pt-1">
        <a href="{{ route('checkout.create') }}"
           class="block w-full text-center py-3.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm hover:shadow-md text-sm flex items-center justify-center gap-2">
            Proceed to Checkout
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>

        <a href="{{ route('products.index') }}"
           class="block w-full py-3 text-center border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition text-sm">
            Continue Shopping
        </a>
    </div>

    <div class="flex items-center justify-center gap-4 pt-2 text-[10px] text-gray-400 uppercase tracking-wider">
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Secure Checkout
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Protected
        </span>
    </div>
</div>
