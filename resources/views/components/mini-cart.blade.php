@props(['items' => [], 'subtotal' => 0])

<div x-data="{
    open: false,
    items: @js($items),
    subtotal: {{ $subtotal }},
    count: {{ array_sum(array_column($items, 'quantity')) }},
    shipping: {{ empty($items) || $subtotal >= 100 ? 0 : 9.99 }},
    discount: {{ (float) session('coupon.discount', 0) }},
    tax: {{ round($subtotal * 0.05, 2) }},
    loading: false,
    busy: false,
    bodyOverflow: '',
    rootOverflow: '',
    bodyPaddingRight: '',
    get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
    get sym() { return '{{ getCurrencySymbol() }}'; },
    get total() { return Math.max(0, this.subtotal + this.shipping + this.tax - this.discount); },
    fmt(n) { return this.sym + Number(n).toFixed(2); },
    emojiFor(item) {
        return '🌿';
    },
    init() {
        this.$watch('open', value => value ? this.lockPageScroll() : this.unlockPageScroll());
    },
    recalculateTotals() {
        this.shipping = this.count === 0 || this.subtotal >= 100 ? 0 : 9.99;
        this.tax = Math.round(this.subtotal * 0.05 * 100) / 100;
    },
    dispatchCartUpdating() {
        window.dispatchEvent(new CustomEvent('cart-updating', {
            detail: {
                count: this.count,
                subtotal: this.subtotal,
                shipping_charge: this.shipping,
                discount: this.discount,
                tax: this.tax,
                grand_total: this.total,
            },
        }));
    },
    openCart() {
        this.open = true;
        this.refresh();
    },
    closeCart() {
        this.open = false;
    },
    lockPageScroll() {
        const root = document.documentElement;
        const body = document.body;
        this.rootOverflow = root.style.overflow;
        this.bodyOverflow = body.style.overflow;
        this.bodyPaddingRight = body.style.paddingRight;
        const scrollbarWidth = window.innerWidth - root.clientWidth;

        root.style.overflow = 'hidden';
        body.style.overflow = 'hidden';
        if (scrollbarWidth > 0) body.style.paddingRight = `${scrollbarWidth}px`;
    },
    unlockPageScroll() {
        document.documentElement.style.overflow = this.rootOverflow;
        document.body.style.overflow = this.bodyOverflow;
        document.body.style.paddingRight = this.bodyPaddingRight;
    },
    applyCart(data) {
        if (!data) return;

        if (Array.isArray(data.items)) {
            this.items = data.items;
        } else if (Number(data.count) === 0) {
            this.items = [];
        } else {
            this.refresh();
        }

        if (data.subtotal !== undefined) this.subtotal = Number(data.subtotal);
        if (data.count !== undefined) this.count = Number(data.count);
        if (data.shipping_charge !== undefined) this.shipping = Number(data.shipping_charge);
        if (data.discount !== undefined) this.discount = Number(data.discount);
        if (data.tax !== undefined) this.tax = Number(data.tax);
        if (data.shipping_charge === undefined || data.tax === undefined) this.recalculateTotals();
    },
    updateEndpoint(id) {
        return '{{ route('cart.update', ['id' => '__cart_item__']) }}'.replace('__cart_item__', encodeURIComponent(id));
    },
    removeEndpoint(id) {
        return '{{ route('cart.remove', ['id' => '__cart_item__']) }}'.replace('__cart_item__', encodeURIComponent(id));
    },
    async refresh() {
        this.loading = true;
        try {
            const res = await fetch('{{ route('cart.items') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) return;
            const data = await res.json();
            this.applyCart(data);
        } catch(e) {}
        finally { this.loading = false; }
    },
    async updateQty(id, qty) {
        const item = this.items.find(i => Number(i.id) === Number(id));
        const nextQty = Number(qty);
        if (!item || nextQty < 1 || nextQty > Number(item.stock) || this.busy) return;

        const previousQty = Number(item.quantity);
        const price = Number(item.final_price ?? item.price);
        item.quantity = nextQty;
        this.count += nextQty - previousQty;
        this.subtotal += price * (nextQty - previousQty);
        this.recalculateTotals();
        this.dispatchCartUpdating();
        this.busy = true;
        try {
            const form = new FormData();
            form.append('_token', this.csrf);
            form.append('quantity', nextQty);
            form.append('_method', 'PATCH');
            const res = await fetch(this.updateEndpoint(id), { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.applyCart(data);
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
        } catch(e) {
            item.quantity = previousQty;
            this.count -= nextQty - previousQty;
            this.subtotal -= price * (nextQty - previousQty);
            this.recalculateTotals();
            this.dispatchCartUpdating();
            window.GG?.error?.('Could not update the quantity. Please try again.');
        }
        finally { this.busy = false; }
    },
    async removeItem(id) {
        if (this.busy) return;
        const index = this.items.findIndex(i => Number(i.id) === Number(id));
        if (index === -1) return;

        const item = this.items[index];
        const removedSubtotal = Number(item.final_price ?? item.price) * Number(item.quantity);
        this.items.splice(index, 1);
        this.count -= Number(item.quantity);
        this.subtotal -= removedSubtotal;
        this.recalculateTotals();
        this.dispatchCartUpdating();
        this.busy = true;
        try {
            const form = new FormData();
            form.append('_token', this.csrf);
            form.append('_method', 'DELETE');
            const res = await fetch(this.removeEndpoint(id), { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: form });
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.applyCart(data);
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
        } catch(e) {
            this.items.splice(index, 0, item);
            this.count += Number(item.quantity);
            this.subtotal += removedSubtotal;
            this.recalculateTotals();
            this.dispatchCartUpdating();
            window.GG?.error?.('Could not remove this item. Please try again.');
        }
        finally { this.busy = false; }
    }
}"
     x-on:open-mini-cart.window="openCart()"
     x-on:cart-updated.window="applyCart($event.detail)"
     x-on:keydown.escape.window="closeCart()">

    {{-- Overlay --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeCart()"
         class="fixed inset-0 bg-ink/40 backdrop-blur-[2px] z-[200]"></div>

    {{-- Panel --}}
    <aside x-show="open" x-cloak
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="translate-x-full"
           role="dialog" aria-modal="true" aria-label="Shopping cart"
           class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-[210] shadow-2xl flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-line bg-cream/60">
            <h3 class="font-display text-lg font-semibold text-ink flex items-center gap-2">
                Your Cart
                <span class="text-xs font-normal text-ink-soft bg-white border border-line px-2 py-0.5 rounded-full" x-text="count + ' item' + (count !== 1 ? 's' : '')"></span>
            </h3>
            <button @click="closeCart()" class="p-2 rounded-lg text-ink-soft hover:text-ink hover:bg-line/60 transition" aria-label="Close cart">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
            <div x-show="loading" x-cloak class="flex items-center justify-center py-16">
                <svg class="animate-spin w-8 h-8 text-brand-700" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
            </div>

            <template x-if="!loading && items.length === 0">
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto rounded-full bg-brand-100 flex items-center justify-center text-3xl select-none">🛒</div>
                    <p class="font-display text-lg font-semibold text-ink mt-4">Your cart is empty</p>
                    <p class="text-sm text-ink-soft mt-1">Add a few plants or goods to get started.</p>
                    <button @click="closeCart(); window.location.href = '{{ route('products.index') }}'"
                            class="gg-btn mt-5 px-6 py-2.5 text-sm">Start Shopping</button>
                </div>
            </template>

            <template x-if="!loading && items.length > 0" x-for="item in items" :key="item.id">
                <div class="flex items-start gap-3 pb-4 border-b border-line last:border-0" :class="busy ? 'opacity-60 pointer-events-none' : ''">
                    <a :href="'/products/' + item.slug" class="shrink-0 w-16 h-16 rounded-xl bg-cream border border-line overflow-hidden flex items-center justify-center">
                        <img x-show="item.image" :src="'/storage/' + item.image" :alt="item.name" class="w-full h-full object-cover">
                        <span x-show="!item.image" class="text-2xl select-none" x-text="emojiFor(item)"></span>
                    </a>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <a :href="'/products/' + item.slug" class="text-sm font-semibold text-ink hover:text-brand-700 transition leading-snug line-clamp-2" x-text="item.name"></a>
                            <button @click="removeItem(item.id)" class="shrink-0 p-1 text-ink-soft hover:text-red-500 transition" aria-label="Remove item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        <p class="text-xs text-brand-700 font-semibold mt-0.5" x-text="fmt(item.final_price ?? item.price)"></p>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-1.5">
                                <button @click="updateQty(item.id, item.quantity - 1)" :disabled="item.quantity <= 1" class="gg-step-btn w-7 h-7" aria-label="Decrease quantity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>
                                <span class="w-8 h-7 flex items-center justify-center border border-line rounded-md text-xs font-semibold text-ink bg-white" x-text="item.quantity"></span>
                                <button @click="updateQty(item.id, item.quantity + 1)" :disabled="item.quantity >= item.stock" class="gg-step-btn w-7 h-7" aria-label="Increase quantity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            <span class="text-sm font-bold text-ink" x-text="fmt((item.final_price ?? item.price) * item.quantity)"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div x-show="items.length > 0" x-cloak class="border-t border-line px-5 py-4 space-y-3 bg-white">
            <div class="flex items-center justify-between text-sm">
                <span class="text-ink-soft">Subtotal</span>
                <span class="font-bold text-ink" x-text="fmt(subtotal)"></span>
            </div>
            <div class="flex items-center justify-between border-t border-line pt-3">
                <span class="font-semibold text-ink">Total</span>
                <span class="font-display text-lg font-semibold text-brand-700" x-text="fmt(total)"></span>
            </div>
            <p class="text-xs text-ink-soft" x-show="subtotal < 100" x-cloak>
                Add <strong class="text-brand-700" x-text="fmt(100 - subtotal)"></strong> more for <strong>Free Shipping</strong>.
            </p>
            <p class="text-xs text-brand-700 font-medium" x-show="subtotal >= 100" x-cloak>✓ Free Shipping unlocked!</p>
            <div class="grid grid-cols-1 gap-2">
                <button @click="closeCart(); window.location.href = '{{ route('cart.index') }}'"
                        class="gg-btn-outline w-full py-3 text-sm">View Cart</button>
                <button @click="closeCart(); window.location.href = '{{ route('checkout.create') }}'"
                        class="gg-btn w-full py-3 text-sm">Proceed to Checkout →</button>
            </div>
        </div>
    </aside>
</div>
