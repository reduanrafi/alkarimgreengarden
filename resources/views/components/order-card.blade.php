@props(['order'])

<a href="{{ route('orders.show', $order) }}" class="gg-panel gg-order-card group">
    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="gg-order-card-icon">
                📦
            </div>
            <div class="min-w-0">
                <p class="font-mono font-bold text-[#173d2b] truncate">#{{ $order->invoice_no ?? str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-[#5b6259]">{{ $order->ordered_at ? $order->ordered_at->format('d M Y, h:i A') : $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <x-order-status :status="$order->status" />
    </div>

    <div class="flex items-center justify-between gap-3">
        <div class="flex -space-x-2">
            @foreach($order->items->take(4) as $item)
                <div class="w-9 h-9 rounded-full border-2 border-white bg-[#e4efe4] flex items-center justify-center text-sm overflow-hidden">
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <span>{{ categoryEmoji($item->product->category->slug ?? null) }}</span>
                    @endif
                </div>
            @endforeach
            @if($order->items->count() > 4)
                <div class="w-9 h-9 rounded-full border-2 border-white bg-[#173d2b] text-white text-[10px] font-bold flex items-center justify-center">
                    +{{ $order->items->count() - 4 }}
                </div>
            @endif
        </div>
        <div class="text-right">
            <p class="font-bold text-[#173d2b]">{{ formatPrice($order->grand_total) }}</p>
            <p class="text-xs text-[#5b6259]">{{ $order->items_count ?? $order->items->count() }} item(s)</p>
        </div>
    </div>
</a>
