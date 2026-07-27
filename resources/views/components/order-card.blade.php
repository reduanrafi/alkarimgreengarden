@props(['order'])

<a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#66D9F1]/10 to-[#4CC9F0]/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 text-[#4CC9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-mono">#{{ $order->id }}</p>
                <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                <p class="text-sm text-gray-500">{{ $order->ordered_at ? $order->ordered_at->format('d M Y, h:i A') : $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 sm:text-right">
            <div>
                <p class="font-bold text-indigo-600">{{ formatPrice($order->grand_total) }}</p>
                <p class="text-xs text-gray-500">{{ $order->items_count ?? $order->items->count() }} item(s)</p>
            </div>
            <x-order-status :status="$order->status" />
        </div>
    </div>
</a>
