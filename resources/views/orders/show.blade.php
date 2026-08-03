@extends('layouts.account')

@section('title', 'Order #' . ($order->invoice_no ?? $order->id) . ' - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Order History</p>
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="gg-title">Order #{{ $order->invoice_no ?? str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <x-order-status :status="$order->status" />
        </div>
        <p class="gg-sub">
            Placed {{ $order->ordered_at ? $order->ordered_at->format('M d, Y h:i A') : $order->created_at->format('M d, Y h:i A') }}
            • {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
        </p>
        <div class="mt-3">
            <a href="{{ route('orders.invoice', $order) }}" class="gg-btn">
                <span class="mr-1.5">🧾</span> Download Invoice
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6 min-w-0">

            {{-- Status Timeline --}}
            <div class="gg-panel" id="timeline">
                <div class="gg-panel-head">
                    <h2 class="gg-title">Status Timeline</h2>
                </div>
                @php
                    $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                    $currentIndex = array_search($order->status, $statuses);
                @endphp
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-[#e6e9e2]"></div>
                    <div class="space-y-6 relative">
                        @foreach($statuses as $i => $s)
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold relative z-10 shrink-0
                                    {{ $i <= $currentIndex ? 'bg-[#3f8a5c] text-white shadow-sm' : 'bg-[#e4efe4] text-[#8a938a]' }}">
                                    @if($i < $currentIndex)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <span>{{ $i + 1 }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $i <= $currentIndex ? 'text-[#173d2b]' : 'text-[#8a938a]' }}">{{ ucfirst($s) }}</p>
                                    @if($i == $currentIndex && $order->ordered_at)
                                        <p class="text-xs text-[#8a938a] mt-0.5">{{ $order->ordered_at->format('d M Y, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="gg-panel">
                <div class="gg-panel-head">
                    <h2 class="gg-title">Order Items</h2>
                </div>
                <div class="divide-y divide-[#e6e9e2]">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-4 {{ $loop->first ? 'pt-0' : '' }} {{ $loop->last ? 'pb-0' : '' }}">
                            <div class="w-16 h-16 rounded-xl bg-[#e4efe4] flex items-center justify-center text-2xl shrink-0 overflow-hidden">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <span>{{ $item->product ? categoryEmoji($item->product->category->slug ?? null) : '📦' }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#173d2b] text-sm">{{ $item->product->name ?? 'Product' }}</p>
                                <div class="flex items-center gap-3 mt-0.5 text-xs text-[#5b6259]">
                                    <span>Qty: {{ $item->quantity }}</span>
                                    <span>{{ formatPrice($item->price) }} each</span>
                                </div>
                            </div>
                            <p class="font-bold text-[#173d2b] text-sm">{{ formatPrice($item->price * $item->quantity) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-[#e6e9e2] pt-4 mt-2 space-y-1.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#5b6259]">Subtotal</span>
                        <span class="font-bold text-[#22281f]">{{ formatPrice($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#5b6259]">Shipping</span>
                        <span class="font-bold">{{ $order->shipping_charge > 0 ? formatPrice($order->shipping_charge) : 'Free' }}</span>
                    </div>
                    @if($order->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-[#5b6259]">Tax</span>
                            <span class="font-bold">{{ formatPrice($order->tax) }}</span>
                        </div>
                    @endif
                    @if($order->discount > 0)
                        <div class="flex justify-between text-[#1f5c3f]">
                            <span>Discount{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}</span>
                            <span class="font-bold">-{{ formatPrice($order->discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base border-t border-[#e6e9e2] pt-3 mt-3">
                        <span class="font-bold text-[#173d2b]">Total</span>
                        <span class="font-bold text-[#173d2b]">{{ formatPrice($order->grand_total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6 min-w-0">
            {{-- Shipping Address --}}
            <div class="gg-panel">
                <div class="gg-panel-head">
                    <h2 class="gg-title">Shipping Address</h2>
                </div>
                <div class="text-sm space-y-1.5 text-[#5b6259]">
                    <p class="font-bold text-[#173d2b]">{{ $order->customer_name }}</p>
                    @if($order->phone)
                        <p>☎️ {{ $order->phone }}</p>
                    @endif
                    @if($order->email)
                        <p>✉️ {{ $order->email }}</p>
                    @endif
                    <div class="mt-3 pt-3 border-t border-[#e6e9e2]">
                        <p>{{ $order->address }}</p>
                        <p>{{ collect([$order->upazila, $order->district])->filter()->implode(', ') }}</p>
                        <p>{{ $order->division }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}</p>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="gg-panel">
                    <div class="gg-panel-head">
                        <h2 class="gg-title">Order Notes</h2>
                    </div>
                    <p class="text-sm text-[#5b6259]">{{ $order->notes }}</p>
                </div>
            @endif

            @if($order->transaction_id)
                <div class="gg-panel">
                    <div class="gg-panel-head">
                        <h2 class="gg-title">Payment</h2>
                    </div>
                    <div class="text-sm space-y-1.5 text-[#5b6259]">
                        <p class="flex justify-between"><span>Status</span><span class="font-bold capitalize text-[#173d2b]">{{ str_replace('_', ' ', $order->payment_status) }}</span></p>
                        <p class="flex justify-between"><span>Transaction</span><span class="font-mono text-xs">{{ $order->transaction_id }}</span></p>
                    </div>
                </div>
            @endif

            <a href="{{ route('orders.index') }}" class="gg-btn-outline w-full">
                ← Back to Orders
            </a>
        </div>
    </div>
@endsection
