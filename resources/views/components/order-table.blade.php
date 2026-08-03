@props(['orders' => collect()])

<div class="gg-panel overflow-hidden">
    {{-- Desktop table --}}
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[11px] uppercase tracking-wider text-[#5b6259] border-b border-[#e6e9e2]">
                    <th class="px-5 py-3.5 font-bold">Order</th>
                    <th class="px-5 py-3.5 font-bold">Customer</th>
                    <th class="px-5 py-3.5 font-bold">Date</th>
                    <th class="px-5 py-3.5 font-bold">Items</th>
                    <th class="px-5 py-3.5 font-bold">Total</th>
                    <th class="px-5 py-3.5 font-bold">Payment</th>
                    <th class="px-5 py-3.5 font-bold">Status</th>
                    <th class="px-5 py-3.5 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e6e9e2]">
                @foreach($orders as $order)
                    <tr class="hover:bg-[#f7f9f6] transition-colors">
                        <td class="px-5 py-4">
                            <a href="{{ route('orders.show', $order) }}" class="font-mono font-bold text-[#173d2b] hover:text-[#1f5c3f]">
                                #{{ $order->invoice_no ?? str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td class="px-5 py-4 font-medium text-[#22281f]">{{ $order->customer_name }}</td>
                        <td class="px-5 py-4 text-[#5b6259]">
                            {{ $order->ordered_at ? $order->ordered_at->format('M d, Y') : $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="w-8 h-8 rounded-full border-2 border-white bg-[#e4efe4] flex items-center justify-center text-sm overflow-hidden">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                            @else
                                                <span>{{ categoryEmoji($item->product->category->slug ?? null) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <span class="text-xs text-[#5b6259]">{{ $order->items_count ?? $order->items->count() }} item(s)</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-bold text-[#173d2b]">{{ formatPrice($order->grand_total) }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-[#e4efe4] text-[#1f5c3f]' : ($order->payment_status === 'failed' ? 'bg-[#fef2f2] text-[#b91c1c]' : 'bg-[#fef3c7] text-[#b45309]') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <x-order-status :status="$order->status" />
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('orders.show', $order) }}" class="gg-chip-link">View</a>
                                <a href="{{ route('orders.show', $order) }}#timeline" class="gg-chip-link">Track</a>
                                <a href="{{ route('orders.invoice', $order) }}" class="gg-chip-link">Invoice</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="lg:hidden divide-y divide-[#e6e9e2]">
        @foreach($orders as $order)
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <a href="{{ route('orders.show', $order) }}" class="font-mono font-bold text-[#173d2b]">
                            #{{ $order->invoice_no ?? str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                        </a>
                        <p class="text-xs text-[#5b6259] mt-0.5">
                            {{ $order->customer_name }} • {{ $order->ordered_at ? $order->ordered_at->format('M d, Y') : $order->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5">
                        <x-order-status :status="$order->status" />
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-[#e4efe4] text-[#1f5c3f]' : ($order->payment_status === 'failed' ? 'bg-[#fef2f2] text-[#b91c1c]' : 'bg-[#fef3c7] text-[#b45309]') }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-3">
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
                    </div>
                    <p class="font-bold text-[#173d2b]">{{ formatPrice($order->grand_total) }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('orders.show', $order) }}" class="gg-btn flex-1 !py-2 !px-3 text-xs">View Details</a>
                    <a href="{{ route('orders.show', $order) }}#timeline" class="gg-btn-outline flex-1 !py-2 !px-3 text-xs">Track</a>
                    <a href="{{ route('orders.invoice', $order) }}" class="gg-btn-outline flex-1 !py-2 !px-3 text-xs">Invoice</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
