@props(['total', 'count'])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5 sticky top-24">
    <h3 class="font-bold text-lg text-gray-900">Cart Summary</h3>

    @php
        $subtotal = $total;
        $shippingCharge = $subtotal >= 100 ? 0 : 9.99;
    @endphp

    <div class="space-y-3 text-sm">
        <div class="flex justify-between text-gray-600">
            <span>Total Items</span>
            <span class="font-medium text-gray-900">{{ $count }}</span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span class="font-medium text-gray-900">{{ formatPrice($subtotal) }}</span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span>Shipping</span>
            <span class="font-medium">
                @if($shippingCharge > 0)
                    {{ formatPrice($shippingCharge) }}
                    <span class="text-xs text-gray-400 block -mt-0.5">Free on orders over $100</span>
                @else
                    <span class="text-emerald-600 font-medium">Free</span>
                @endif
            </span>
        </div>
        @if(session('coupon.discount', 0) > 0)
            <div class="flex justify-between text-emerald-600">
                <span>Coupon Discount</span>
                <span class="font-medium">-{{ formatPrice(session('coupon.discount')) }}</span>
            </div>
        @endif
        <div class="border-t border-gray-100 pt-3 flex justify-between text-base">
            <span class="font-semibold text-gray-900">Estimated Total</span>
            <span class="font-bold text-lg text-indigo-600">{{ formatPrice($subtotal + $shippingCharge - session('coupon.discount', 0)) }}</span>
        </div>
    </div>

    <div class="space-y-3 pt-1">
        <a href="{{ route('checkout.create') }}"
           class="block w-full text-center py-3.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm hover:shadow-md text-sm">
            Proceed to Checkout
        </a>

        <a href="{{ route('products.index') }}"
           class="block w-full py-3 text-center border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition text-sm">
            Continue Shopping
        </a>
    </div>

    <div class="flex items-center justify-center gap-4 pt-2 text-[10px] text-gray-400 uppercase tracking-wider">
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Secure
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Protected
        </span>
    </div>
</div>
