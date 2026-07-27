@props(['item'])

<div x-data="{
    updating: false,
    removing: false,
    qty: {{ $item['quantity'] }},
    max: {{ $item['stock'] }},
    get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
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
            const res = await fetch('/cart/update/{{ $item['id'] }}', { method: 'POST', body: form });
            if (!res.ok) throw new Error();
            window.dispatchEvent(new CustomEvent('cart-updated'));
        } catch(e) { this.qty -= change; }
        finally { this.updating = false; }
    },
    async removeItem() {
        if (!confirm('Remove this item?')) return;
        this.removing = true;
        try {
            const form = new FormData();
            form.append('_token', this.csrf);
            form.append('_method', 'DELETE');
            const res = await fetch('/cart/remove/{{ $item['id'] }}', { method: 'POST', body: form });
            if (!res.ok) throw new Error();
            window.location.reload();
        } catch(e) { this.removing = false; }
    }
}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 transition hover:shadow-md relative">
    <div x-show="removing" x-cloak class="absolute inset-0 bg-white/80 rounded-2xl flex items-center justify-center z-10">
        <svg class="animate-spin w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
    </div>
    <div class="flex items-center gap-4 sm:gap-6">
        <a href="{{ route('products.show', $item['slug']) }}"
           class="shrink-0 w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center text-3xl overflow-hidden">
            @if(!empty($item['image']))
                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" loading="lazy">
            @else
                <div class="text-3xl">
                    @switch($item['category_slug'] ?? '')
                        @case('mens-t-shirt') 👕 @break
                        @case('womens-t-shirt') 👚 @break
                        @case('bags') 👜 @break
                        @default ✨
                    @endswitch
                </div>
            @endif
        </a>

        <div class="flex-1 min-w-0">
            <a href="{{ route('products.show', $item['slug']) }}"
               class="font-semibold text-gray-900 hover:text-indigo-600 transition text-sm sm:text-base block truncate">
                {{ $item['name'] }}
            </a>
            @if(!empty($item['category_slug']))
                <p class="text-xs text-gray-400 mt-0.5">{{ $item['category_name'] ?? '' }}</p>
            @endif
            <p class="text-indigo-600 font-bold text-sm sm:text-base mt-1">{{ formatPrice($item['price']) }}</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2" :class="updating ? 'opacity-50 pointer-events-none' : ''">
                <button type="button" @click="updateQty(-1)" :disabled="qty <= 1"
                        class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">-</button>
                <span class="w-14 h-9 flex items-center justify-center border border-gray-200 rounded-xl text-sm font-medium text-gray-900 bg-white" x-text="qty"></span>
                <button type="button" @click="updateQty(1)" :disabled="qty >= max"
                        class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">+</button>
            </div>
            <div x-show="updating" x-cloak>
                <svg class="animate-spin w-5 h-5 text-indigo-500" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
            </div>
        </div>

        <div class="text-right shrink-0 min-w-[80px]">
            <p class="font-bold text-gray-900 text-sm sm:text-base">{{ formatPrice($item['price'] * $item['quantity']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5" x-text="qty + ' × {{ formatPrice($item['price']) }}'"></p>
            <button type="button" @click="removeItem()"
                    class="text-xs text-red-400 hover:text-red-600 transition mt-1.5 flex items-center gap-1 ml-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Remove
            </button>
        </div>
    </div>
</div>
