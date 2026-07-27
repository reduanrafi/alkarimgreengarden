@props(['cartItems' => [], 'subtotal' => 0, 'shippingCharge' => 0, 'discount' => 0, 'grandTotal' => 0])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
    <div class="space-y-3 max-h-72 overflow-y-auto pr-1 custom-scrollbar">
        @foreach($cartItems as $item)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xl shrink-0 overflow-hidden">
                    @if(!empty($item['image']))
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
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
                <p class="text-sm font-semibold text-gray-900">{{ formatPrice($item['price'] * $item['quantity']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="border-t border-gray-100 pt-4 space-y-2.5 text-sm">
        <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span class="font-medium text-gray-900">{{ formatPrice($subtotal) }}</span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span>Shipping</span>
            <span class="font-medium">{{ $shippingCharge > 0 ? formatPrice($shippingCharge) : 'Free' }}</span>
        </div>
        @if($discount > 0)
            <div class="flex justify-between text-emerald-600">
                <span>Discount</span>
                <span class="font-medium">-{{ formatPrice($discount) }}</span>
            </div>
        @endif
        <div class="border-t border-gray-100 pt-3 flex justify-between text-base">
            <span class="font-semibold text-gray-900">Total</span>
            <span id="grandTotalDisplay" class="font-bold text-lg text-indigo-600">{{ formatPrice($grandTotal) }}</span>
        </div>
    </div>
</div>