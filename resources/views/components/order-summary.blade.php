@props(['cartItems' => [], 'subtotal' => 0, 'shippingCharge' => 0, 'discount' => 0, 'grandTotal' => 0, 'tax' => 0])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Order Summary
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ count($cartItems) }} item{{ count($cartItems) !== 1 ? 's' : '' }}</span>
        </h3>
    </div>

    <div class="px-5 sm:px-6 py-4 space-y-3 max-h-72 overflow-y-auto custom-scrollbar">
        @foreach($cartItems as $item)
            <div class="flex items-center gap-3 {{ !$loop->last ? 'pb-3 border-b border-gray-50' : '' }}">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xl shrink-0 overflow-hidden skeleton-sm">
                    @if(!empty($item['image']))
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover fade-img relative" loading="lazy">
                    @else
                        @switch($item['category_slug'] ?? '')
                            @case('mens-t-shirt') 👕 @break
                            @case('womens-t-shirt') 👚 @break
                            @case('bags') 👜 @break
                            @default ✨
                        @endswitch
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                    <p class="text-xs text-gray-400">Qty: {{ $item['quantity'] }}</p>
                </div>
                <p class="text-sm font-semibold text-gray-900 whitespace-nowrap">{{ formatPrice(($item['final_price'] ?? $item['price']) * $item['quantity']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="px-5 sm:px-6 py-5 border-t border-gray-100 space-y-2.5 text-sm">
        <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span class="font-medium text-gray-900">{{ formatPrice($subtotal) }}</span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span>Shipping</span>
            <span class="font-medium {{ $shippingCharge > 0 ? 'text-gray-900' : 'text-emerald-600' }}">{{ $shippingCharge > 0 ? formatPrice($shippingCharge) : 'Free' }}</span>
        </div>
        @if($discount > 0)
            <div class="flex justify-between text-emerald-600">
                <span>Coupon Discount</span>
                <span class="font-medium">-{{ formatPrice($discount) }}</span>
            </div>
        @endif
        @if($tax > 0)
            <div class="flex justify-between text-gray-600">
                <span>Estimated Tax (5%)</span>
                <span class="font-medium text-gray-900">{{ formatPrice($tax) }}</span>
            </div>
        @endif
        <div class="border-t border-gray-100 pt-3 flex justify-between text-base">
            <span class="font-semibold text-gray-900">Grand Total</span>
            <span class="font-bold text-lg text-indigo-600">{{ formatPrice($grandTotal) }}</span>
        </div>
    </div>
</div>
