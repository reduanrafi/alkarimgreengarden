@props(['old' => []])

<form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="space-y-6" x-data="{ billingSame: true, shippingMethod: 'standard', paymentMethod: 'cash_on_delivery' }">
    @csrf

    {{-- Shipping Address --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-5">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Shipping Address</h2>
                <p class="text-xs text-gray-400">Where should we deliver your order?</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('customer_name') border-red-300 bg-red-50 @enderror"
                       placeholder="John Doe">
                @error('customer_name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('phone') border-red-300 bg-red-50 @enderror"
                       placeholder="01XXXXXXXXX">
                @error('phone') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('email') border-red-300 bg-red-50 @enderror"
                   placeholder="john@example.com">
            @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div class="border-t border-gray-100 pt-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Division <span class="text-red-500">*</span></label>
                    <select name="division" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('division') border-red-300 bg-red-50 @enderror">
                        <option value="">Select Division</option>
                        @foreach(['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'] as $div)
                            <option value="{{ $div }}" {{ old('division') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                    @error('division') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">District <span class="text-red-500">*</span></label>
                    <input type="text" name="district" value="{{ old('district') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('district') border-red-300 bg-red-50 @enderror"
                           placeholder="Dhaka">
                    @error('district') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                           placeholder="Dhaka">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Area / Thana</label>
                    <input type="text" name="area" value="{{ old('area') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                           placeholder="Mirpur">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                           placeholder="1216">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Upazila</label>
                    <input type="text" name="upazila" value="{{ old('upazila') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                           placeholder="Mirpur">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                <textarea name="address" rows="2"
                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('address') border-red-300 bg-red-50 @enderror resize-none"
                          placeholder="House #, Road #, Area">{{ old('address') }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4 flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="save_address" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('save_address') ? 'checked' : '' }}>
                    <span class="text-sm text-gray-600">Save this address for future orders</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Billing Address --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-5">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Billing Address</h2>
                <p class="text-xs text-gray-400">Where should the invoice be sent?</p>
            </div>
        </div>

        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-indigo-100 bg-indigo-50/30 cursor-pointer transition-all hover:border-indigo-200">
            <input type="checkbox" name="billing_same_as_shipping" value="1" x-model="billingSame" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
            <div>
                <span class="text-sm font-semibold text-gray-900">Same as Shipping Address</span>
                <p class="text-xs text-gray-500">Use the shipping address for billing</p>
            </div>
        </label>

        <div x-show="!billingSame" x-cloak class="space-y-5 pt-2 border-t border-gray-100">
            <p class="text-sm text-amber-600 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                Fill in your billing address details
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Billing Name</label>
                    <input type="text" name="billing_name" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="John Doe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Billing Phone</label>
                    <input type="tel" name="billing_phone" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" placeholder="01XXXXXXXXX">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Billing Address</label>
                <textarea name="billing_address" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition resize-none" placeholder="House #, Road #, Area"></textarea>
            </div>
        </div>
    </div>

    {{-- Shipping Method --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-4">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Shipping Method</h2>
                <p class="text-xs text-gray-400">Choose your preferred delivery method</p>
            </div>
        </div>

        @error('shipping_method') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm">
            <input type="radio" name="shipping_method" value="standard" x-model="shippingMethod" class="accent-indigo-600 w-4 h-4">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Standard Delivery</span>
                    <p class="text-xs text-gray-400">5-7 business days</p>
                </div>
                <span class="ml-auto text-sm font-semibold text-gray-900" x-text="'{{ formatPrice(9.99) }}'"></span>
            </div>
        </label>

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm">
            <input type="radio" name="shipping_method" value="express" x-model="shippingMethod" class="accent-indigo-600 w-4 h-4">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Express Delivery</span>
                    <p class="text-xs text-gray-400">1-2 business days</p>
                </div>
                <span class="ml-auto text-sm font-semibold text-gray-900" x-text="'{{ formatPrice(19.99) }}'"></span>
            </div>
        </label>

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm">
            <input type="radio" name="shipping_method" value="store_pickup" x-model="shippingMethod" class="accent-indigo-600 w-4 h-4">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Store Pickup</span>
                    <p class="text-xs text-gray-400">Pick up from our store</p>
                </div>
                <span class="ml-auto text-sm font-semibold text-emerald-600">Free</span>
            </div>
        </label>
    </div>

    {{-- Payment Method --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-4">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Payment Method</h2>
                <p class="text-xs text-gray-400">Select your payment option</p>
            </div>
        </div>

        @error('payment_method') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm">
            <input type="radio" name="payment_method" value="cash_on_delivery" x-model="paymentMethod" class="accent-indigo-600 w-4 h-4" checked>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Cash on Delivery</span>
                    <p class="text-xs text-gray-400">Pay when you receive your order</p>
                </div>
            </div>
        </label>

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 opacity-60 cursor-not-allowed">
            <input type="radio" name="payment_method" value="sslcommerz" class="accent-indigo-600 w-4 h-4" disabled>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">SSLCommerz</span>
                    <p class="text-xs text-amber-500 font-medium">Coming Soon</p>
                </div>
            </div>
        </label>

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 opacity-60 cursor-not-allowed">
            <input type="radio" name="payment_method" value="card" class="accent-indigo-600 w-4 h-4" disabled>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Card Payment</span>
                    <p class="text-xs text-amber-500 font-medium">Coming Soon</p>
                </div>
            </div>
        </label>
    </div>

    {{-- Order Notes --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Order Notes</h2>
                <p class="text-xs text-gray-400">Special instructions for delivery</p>
            </div>
        </div>
        <textarea name="notes" rows="2" class="w-full mt-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition resize-none" placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
    </div>
</form>
