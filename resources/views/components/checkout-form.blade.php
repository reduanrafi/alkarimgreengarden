@props(['old' => []])

<form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="space-y-6"
      x-data="{
        billingSame: true,
        shippingMethod: 'standard',
        paymentMethod: 'cash_on_delivery',
        checkoutToken: window.crypto && crypto.randomUUID ? crypto.randomUUID() : ('co-' + Date.now() + '-' + Math.random().toString(36).slice(2)),
        init() {
            this.$watch('shippingMethod', v => window.dispatchEvent(new CustomEvent('checkout-shipping', { detail: v })));
            this.$nextTick(() => window.dispatchEvent(new CustomEvent('checkout-shipping', { detail: this.shippingMethod })));
        }
      }"
      @submit.prevent="window.dispatchEvent(new CustomEvent('checkout-submit'))">
    @csrf
    <input type="hidden" name="checkout_token" :value="checkoutToken">

    {{-- Shipping Address --}}
    <div id="shipping-address" class="bg-white rounded-2xl border border-line shadow-sm p-6 sm:p-8 space-y-5 scroll-mt-28">
        <div class="flex items-center gap-3">
            <div class="gg-icon-tile">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-ink">Shipping Address</h2>
                <p class="text-xs text-ink-soft">Where should we deliver your order?</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="gg-field-label" for="customer_name">Full Name <span class="text-red-500">*</span></label>
                <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                       class="gg-input @error('customer_name') border-red-400 bg-red-50 @enderror"
                       placeholder="John Doe" required>
                @error('customer_name') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="gg-field-label" for="phone">Phone <span class="text-red-500">*</span></label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                       class="gg-input @error('phone') border-red-400 bg-red-50 @enderror"
                       placeholder="01XXXXXXXXX" required>
                @error('phone') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="gg-field-label" for="email">Email <span class="text-red-500">*</span></label>
            <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                   class="gg-input @error('email') border-red-400 bg-red-50 @enderror"
                   placeholder="john@example.com" required>
            @error('email') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div class="border-t border-line pt-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="gg-field-label" for="division">Division <span class="text-red-500">*</span></label>
                    <select id="division" name="division" class="gg-input @error('division') border-red-400 bg-red-50 @enderror" required>
                        <option value="">Select Division</option>
                        @foreach(['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'] as $div)
                            <option value="{{ $div }}" {{ old('division') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                    @error('division') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="gg-field-label" for="district">District <span class="text-red-500">*</span></label>
                    <input id="district" type="text" name="district" value="{{ old('district') }}"
                           class="gg-input @error('district') border-red-400 bg-red-50 @enderror"
                           placeholder="Dhaka" required>
                    @error('district') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="gg-field-label" for="city">City</label>
                    <input id="city" type="text" name="city" value="{{ old('city', auth()->user()->city ?? '') }}"
                           class="gg-input"
                           placeholder="Dhaka">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="gg-field-label" for="area">Area / Thana</label>
                    <input id="area" type="text" name="area" value="{{ old('area') }}"
                           class="gg-input"
                           placeholder="Mirpur">
                </div>
                <div>
                    <label class="gg-field-label" for="postal_code">Postal Code</label>
                    <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}"
                           class="gg-input"
                           placeholder="1216">
                </div>
                <div>
                    <label class="gg-field-label" for="upazila">Upazila</label>
                    <input id="upazila" type="text" name="upazila" value="{{ old('upazila') }}"
                           class="gg-input"
                           placeholder="Mirpur">
                </div>
            </div>

            <div class="mt-4">
                <label class="gg-field-label" for="address">Address <span class="text-red-500">*</span></label>
                <textarea id="address" name="address" rows="2"
                          class="gg-input resize-none @error('address') border-red-400 bg-red-50 @enderror"
                          placeholder="House #, Road #, Area" required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                @error('address') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2.5 mt-5 cursor-pointer group">
                <input type="checkbox" name="save_address" value="1" {{ old('save_address') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-line text-brand-700 focus:ring-brand-500">
                <span class="text-sm text-ink-soft group-hover:text-ink transition">Save this address for future orders</span>
            </label>
        </div>
    </div>

    {{-- Billing Address --}}
    <div class="bg-white rounded-2xl border border-line shadow-sm p-6 sm:p-8 space-y-5">
        <div class="flex items-center gap-3">
            <div class="gg-icon-tile bg-brand-100 text-brand-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-ink">Billing Address</h2>
                <p class="text-xs text-ink-soft">Where should the invoice be sent?</p>
            </div>
        </div>

        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-brand-100 bg-brand-50/40 cursor-pointer transition-all hover:border-brand-200">
            <input type="checkbox" name="billing_same_as_shipping" value="1" x-model="billingSame" checked
                   class="w-5 h-5 rounded border-line text-brand-700 focus:ring-brand-500">
            <div>
                <span class="text-sm font-semibold text-ink">Same as Shipping Address</span>
                <p class="text-xs text-ink-soft">Use the shipping address for billing</p>
            </div>
        </label>

        <div x-show="!billingSame" x-cloak class="space-y-5 pt-2 border-t border-line">
            <p class="text-sm text-amber-600 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                Fill in your billing address details
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="gg-field-label" for="billing_name">Billing Name</label>
                    <input id="billing_name" type="text" name="billing_name" class="gg-input" placeholder="John Doe">
                </div>
                <div>
                    <label class="gg-field-label" for="billing_phone">Billing Phone</label>
                    <input id="billing_phone" type="tel" name="billing_phone" class="gg-input" placeholder="01XXXXXXXXX">
                </div>
            </div>
            <div>
                <label class="gg-field-label" for="billing_address">Billing Address</label>
                <textarea id="billing_address" name="billing_address" rows="2" class="gg-input resize-none" placeholder="House #, Road #, Area"></textarea>
            </div>
        </div>
    </div>

    {{-- Shipping Method --}}
    <div class="bg-white rounded-2xl border border-line shadow-sm p-6 sm:p-8 space-y-4">
        <div class="flex items-center gap-3">
            <div class="gg-icon-tile bg-amber-100 text-amber-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-ink">Shipping Method</h2>
                <p class="text-xs text-ink-soft">Choose your preferred delivery method</p>
            </div>
        </div>

        @error('shipping_method') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror

        <label class="gg-radio-card">
            <input type="radio" name="shipping_method" value="standard" x-model="shippingMethod" checked>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-brand-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-ink">Standard Delivery</span>
                    <p class="text-xs text-ink-soft">5-7 business days</p>
                </div>
                <span class="ml-auto text-sm font-semibold text-ink">{{ formatPrice(9.99) }}</span>
            </div>
        </label>

        <label class="gg-radio-card">
            <input type="radio" name="shipping_method" value="express" x-model="shippingMethod">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-ink">Express Delivery</span>
                    <p class="text-xs text-ink-soft">1-2 business days</p>
                </div>
                <span class="ml-auto text-sm font-semibold text-ink">{{ formatPrice(19.99) }}</span>
            </div>
        </label>

        <label class="gg-radio-card">
            <input type="radio" name="shipping_method" value="store_pickup" x-model="shippingMethod">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-brand-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-ink">Store Pickup</span>
                    <p class="text-xs text-ink-soft">Pick up from our store</p>
                </div>
                <span class="ml-auto text-sm font-semibold text-brand-700">Free</span>
            </div>
        </label>
    </div>

    {{-- Payment Method --}}
    <x-payment-methods />

    {{-- Order Notes --}}
    <div class="bg-white rounded-2xl border border-line shadow-sm p-6 sm:p-8">
        <div class="flex items-center gap-3">
            <div class="gg-icon-tile bg-gray-100 text-ink-soft">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-ink">Order Notes</h2>
                <p class="text-xs text-ink-soft">Special instructions for delivery</p>
            </div>
        </div>
        <textarea name="notes" rows="2" class="gg-input resize-none mt-4" placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
    </div>
</form>
