@props(['item'])

<div x-data="{
    updating: false,
    removing: false,
    qty: {{ $item['quantity'] }},
    max: {{ $item['stock'] }},
    price: {{ $item['final_price'] ?? $item['price'] }},
    get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
    get itemTotal() { return this.price * this.qty; },
    get sym() { return '{{ getCurrencySymbol() }}'; },
    async updateQty(change) {
        const newQty = this.qty + change;
        if (newQty < 1 || newQty > this.max) return;
        this.qty = newQty;
        this.updating = true;
        try {
            const form = new FormData();
            form.append('_token', this.csrf);
            form.append('quantity', this.qty);
            form.append('_method', 'PATCH');
            const res = await fetch('/cart/update/{{ $item['id'] }}', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
            if (!res.ok) throw new Error();
            const data = await res.json();
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
        } catch(e) {
            this.qty -= change;
            window.GG?.error ? window.GG.error('Could not update the quantity. Please try again.') : null;
        }
        finally { this.updating = false; }
    },
    async removeItem() {
        if (this.removing) return;
        this.removing = true;
        try {
            const form = new FormData();
            form.append('_token', this.csrf);
            form.append('_method', 'DELETE');
            const res = await fetch('/cart/remove/{{ $item['id'] }}', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.$el.remove();
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
        } catch(e) {
            this.removing = false;
            window.GG?.error ? window.GG.error('Could not remove this item. Please try again.') : null;
        }
    }
}" class="bg-white rounded-2xl border border-line shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md group relative">
    <div x-show="removing" x-cloak class="absolute inset-0 bg-white/85 rounded-2xl flex items-center justify-center z-10">
        <svg class="animate-spin w-8 h-8 text-brand-700" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
    </div>
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 sm:p-5">
        <a href="{{ route('products.show', $item['slug']) }}" class="shrink-0 w-[100px] h-[100px] sm:w-[110px] sm:h-[110px] bg-cream rounded-xl overflow-hidden border border-line">
            @if(!empty($item['image']))
                <div class="w-full h-full skeleton-sm">
                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover fade-img relative" loading="lazy">
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center text-4xl select-none">
                    @switch($item['category_slug'] ?? '')
                        @case('mens-t-shirt') 👕 @break
                        @case('womens-t-shirt') 👚 @break
                        @case('bags') 👜 @break
                        @case('others') 🪴 @break
                        @default 🌿
                    @endswitch
                </div>
            @endif
        </a>

        <div class="flex-1 min-w-0 w-full">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('products.show', $item['slug']) }}" class="font-semibold text-ink hover:text-brand-700 transition text-sm sm:text-base leading-snug line-clamp-2">{{ $item['name'] }}</a>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                        @if(!empty($item['category_name']))
                            <span class="text-xs text-ink-soft">{{ $item['category_name'] }}</span>
                        @endif
                        <span class="text-xs text-ink-soft/70 font-mono">SKU: {{ $item['sku'] ?? 'FSN-'.str_pad($item['id'], 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        @if(!empty($item['discount_price']))
                            <span class="text-lg font-bold text-brand-700">{{ formatPrice($item['final_price'] ?? $item['discount_price']) }}</span>
                            <span class="text-sm text-ink-soft line-through">{{ formatPrice($item['price']) }}</span>
                            @php
                                $discountPct = $item['discount_type'] === 'percentage'
                                    ? round($item['discount_price'])
                                    : round((1 - $item['discount_price'] / $item['price']) * 100);
                            @endphp
                            <span class="text-[10px] font-bold text-white bg-[#c1521f] px-1.5 py-0.5 rounded-full">-{{ $discountPct }}%</span>
                        @else
                            <span class="text-lg font-bold text-brand-700">{{ formatPrice($item['price']) }}</span>
                        @endif
                    </div>
                    @if($item['stock_status'] === 'low_stock')
                        <p class="text-xs text-amber-600 mt-1">Only {{ $item['stock'] }} left in stock</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-line">
                <div class="flex items-center gap-2" :class="updating ? 'opacity-50 pointer-events-none' : ''">
                    <button type="button" @click="updateQty(-1)" :disabled="qty <= 1"
                            class="gg-step-btn" aria-label="Decrease quantity">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <span class="w-12 h-8 flex items-center justify-center border border-line rounded-lg text-sm font-semibold text-ink bg-white" x-text="qty"></span>
                    <button type="button" @click="updateQty(1)" :disabled="qty >= max"
                            class="gg-step-btn" aria-label="Increase quantity">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <div x-show="updating" x-cloak>
                        <svg class="animate-spin w-4 h-4 text-brand-700" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-base font-bold text-ink" x-text="sym + Number(itemTotal).toFixed(2)"></span>
                    <span class="text-xs text-ink-soft hidden sm:inline" x-text="qty + ' \u00d7 ' + sym + Number(price).toFixed(2)"></span>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-2 pt-2 border-t border-line">
                @auth
                    <form action="{{ route('wishlist.toggle', $item['id']) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-ink-soft hover:text-red-500 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Move to Wishlist
                        </button>
                    </form>
                @endauth
                <button type="button" @click="removeItem()" class="text-xs text-red-400 hover:text-red-600 transition flex items-center gap-1 ml-auto sm:ml-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>
