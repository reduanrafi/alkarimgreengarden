@props(['items' => [], 'subtotal' => 0])

<div
    x-data="{
        open: false,
        loading: false,
        updating: false,
        removingId: null,
        items: @js($items),
        subtotal: Number({{ $subtotal }}),
        count: Number({{ array_sum(array_column($items, 'quantity')) }}),
        storageUrl: '{{ asset('storage') }}',
        bodyOverflow: '',
        rootOverflow: '',
        bodyPaddingRight: '',

        get csrf() { return document.querySelector('meta[name=csrf-token]')?.content || ''; },
        get currency() { return '{{ getCurrencySymbol() }}'; },
        get total() { return Math.max(0, this.subtotal); },

        format(amount) {
            return this.currency + Number(amount || 0).toFixed(2);
        },
        unitPrice(item) {
            return Number(item.final_price ?? item.price ?? 0);
        },
        lineSubtotal(item) {
            return this.unitPrice(item) * Number(item.quantity || 0);
        },
        imageUrl(item) {
            if (!item.image) return '';
            return item.image.startsWith('http') ? item.image : this.storageUrl + '/' + String(item.image).replace(/^\/+/, '');
        },
        updateUrl(id) {
            return '{{ route('cart.update', ['id' => '__cart_item__']) }}'.replace('__cart_item__', encodeURIComponent(id));
        },
        removeUrl(id) {
            return '{{ route('cart.remove', ['id' => '__cart_item__']) }}'.replace('__cart_item__', encodeURIComponent(id));
        },

        init() {
            this.$watch('open', isOpen => isOpen ? this.lockScroll() : this.unlockScroll());
        },
        openCart(cart = null) {
            this.open = true;

            if (cart && Array.isArray(cart.items)) {
                this.applyCart(cart);
                return;
            }

            this.refresh();
        },
        closeCart() {
            this.open = false;
        },
        lockScroll() {
            const root = document.documentElement;
            const body = document.body;
            this.rootOverflow = root.style.overflow;
            this.bodyOverflow = body.style.overflow;
            this.bodyPaddingRight = body.style.paddingRight;
            const scrollbarWidth = window.innerWidth - root.clientWidth;

            root.style.overflow = 'hidden';
            body.style.overflow = 'hidden';
            if (scrollbarWidth > 0) body.style.paddingRight = scrollbarWidth + 'px';
        },
        unlockScroll() {
            document.documentElement.style.overflow = this.rootOverflow;
            document.body.style.overflow = this.bodyOverflow;
            document.body.style.paddingRight = this.bodyPaddingRight;
        },
        applyCart(cart) {
            if (!cart) return;

            if (Array.isArray(cart.items)) this.items = cart.items;
            if (cart.count !== undefined) this.count = Number(cart.count);
            if (cart.subtotal !== undefined) this.subtotal = Number(cart.subtotal);
        },
        announceCart(cart) {
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: cart }));
        },
        async refresh() {
            this.loading = true;
            try {
                const response = await fetch('{{ route('cart.items') }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (!response.ok) throw new Error('Unable to load cart');

                this.applyCart(await response.json());
            } catch (error) {
                window.GG?.error?.('Could not refresh your cart. Please try again.');
            } finally {
                this.loading = false;
            }
        },
        async changeQuantity(item, change) {
            if (this.updating) return;

            const previousQuantity = Number(item.quantity);
            const quantity = previousQuantity + change;
            const stock = Number(item.stock);
            if (quantity < 1 || quantity > stock) return;

            const previousSubtotal = this.subtotal;
            const previousCount = this.count;
            item.quantity = quantity;
            this.count += change;
            this.subtotal += this.unitPrice(item) * change;
            this.updating = true;

            try {
                const form = new FormData();
                form.append('_token', this.csrf);
                form.append('_method', 'PATCH');
                form.append('quantity', quantity);

                const response = await fetch(this.updateUrl(item.id), {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: form,
                });
                if (!response.ok) throw new Error('Unable to update cart');

                const cart = await response.json();
                this.applyCart(cart);
                this.announceCart(cart);
            } catch (error) {
                item.quantity = previousQuantity;
                this.count = previousCount;
                this.subtotal = previousSubtotal;
                window.GG?.error?.('Could not update the quantity. Please try again.');
            } finally {
                this.updating = false;
            }
        },
        async removeItem(item) {
            if (this.removingId === item.id) return;

            this.removingId = item.id;
            try {
                const form = new FormData();
                form.append('_token', this.csrf);
                form.append('_method', 'DELETE');

                const response = await fetch(this.removeUrl(item.id), {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: form,
                });
                if (!response.ok) throw new Error('Unable to remove item');

                const cart = await response.json();
                this.applyCart(cart);
                this.announceCart(cart);
            } catch (error) {
                window.GG?.error?.('Could not remove this item. Please try again.');
            } finally {
                this.removingId = null;
            }
        },
    }"
    x-on:open-mini-cart.window="openCart($event.detail)"
    x-on:cart-updated.window="applyCart($event.detail)"
    x-on:keydown.escape.window="closeCart()"
>
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closeCart()"
        class="fixed inset-0 z-[200] bg-ink/40 backdrop-blur-[2px]"
    ></div>

    <aside
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        role="dialog"
        aria-modal="true"
        aria-label="Shopping cart"
        :aria-busy="loading || updating"
        class="fixed top-0 right-0 z-[210] flex h-full w-full max-w-md flex-col bg-white shadow-2xl"
    >
        <header class="flex items-center justify-between border-b border-line bg-cream/60 px-5 py-4">
            <h2 class="font-display text-lg font-semibold text-ink">
                Your Cart
                <span class="ml-1 rounded-full border border-line bg-white px-2 py-0.5 text-xs font-normal text-ink-soft" x-text="count + ' item' + (count === 1 ? '' : 's')"></span>
            </h2>
            <button type="button" @click="closeCart()" class="rounded-lg p-2 text-ink-soft transition hover:bg-line/60 hover:text-ink" aria-label="Close cart">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </header>

        <div class="flex-1 overflow-y-auto px-5 py-4">
            <div x-show="loading" x-cloak class="flex justify-center py-16" aria-label="Loading cart">
                <svg class="h-8 w-8 animate-spin text-brand-700" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"/></svg>
            </div>

            <div x-show="!loading && items.length === 0" x-cloak class="py-16 text-center">
                <p class="font-display text-lg font-semibold text-ink">Your cart is empty.</p>
            </div>

            <div x-show="!loading && items.length > 0" x-cloak class="space-y-4">
                <template x-for="item in items" :key="item.id">
                    <article class="flex gap-3 border-b border-line pb-4 last:border-0" :class="{ 'pointer-events-none opacity-60': updating }">
                        <a :href="'/products/' + item.slug" class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-line bg-cream">
                            <img x-show="item.image" :src="imageUrl(item)" :alt="item.name" class="h-full w-full object-cover">
                            <span x-show="!item.image" class="flex h-full w-full items-center justify-center text-2xl" aria-hidden="true">🌿</span>
                        </a>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <a :href="'/products/' + item.slug" class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition hover:text-brand-700" x-text="item.name"></a>
                                <button type="button" @click="removeItem(item)" :disabled="removingId === item.id"
                                        class="shrink-0 rounded-md p-1 text-ink-soft transition hover:bg-red-50 hover:text-red-500 disabled:opacity-50"
                                        :aria-label="'Remove ' + item.name">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs font-semibold text-brand-700">
                                Unit price: <span x-text="format(unitPrice(item))"></span>
                            </p>

                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="changeQuantity(item, -1)" :disabled="item.quantity <= 1" class="gg-step-btn h-7 w-7" aria-label="Decrease quantity">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="flex h-7 w-8 items-center justify-center rounded-md border border-line bg-white text-xs font-semibold text-ink" x-text="item.quantity"></span>
                                    <button type="button" @click="changeQuantity(item, 1)" :disabled="item.quantity >= item.stock" class="gg-step-btn h-7 w-7" aria-label="Increase quantity">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                <p class="text-right text-sm font-bold text-ink">
                                    <span class="block text-[10px] font-medium uppercase tracking-wide text-ink-soft">Line subtotal</span>
                                    <span x-text="format(lineSubtotal(item))"></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </template>
            </div>
        </div>

        <footer class="border-t border-line bg-white px-5 py-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-ink-soft">Subtotal</span>
                    <span class="font-bold text-ink" x-text="format(subtotal)"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-ink-soft">Delivery</span>
                    <span class="font-semibold text-brand-700">Free</span>
                </div>
                <div class="flex items-center justify-between border-t border-line pt-3">
                    <span class="font-semibold text-ink">Total</span>
                    <span class="font-display text-lg font-semibold text-brand-700" x-text="format(total)"></span>
                </div>
                <button type="button" @click="closeCart(); window.location.href = '{{ route('checkout.create') }}'" :disabled="items.length === 0" class="gg-btn w-full py-3 text-sm disabled:cursor-not-allowed">
                    Proceed to Checkout
                </button>
            </div>
        </footer>
    </aside>
</div>
