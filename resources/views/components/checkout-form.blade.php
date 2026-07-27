@props(['old' => []])

<form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="space-y-6">
    @csrf

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-5">
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
                <input type="text" name="phone" value="{{ old('phone') }}"
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
            <h4 class="text-sm font-semibold text-gray-900 mb-4">Address</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Division <span class="text-red-500">*</span></label>
                    <input type="text" name="division" value="{{ old('division') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('division') border-red-300 bg-red-50 @enderror"
                           placeholder="Dhaka">
                    @error('division') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">District <span class="text-red-500">*</span></label>
                    <input type="text" name="district" value="{{ old('district') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('district') border-red-300 bg-red-50 @enderror"
                           placeholder="Dhaka">
                    @error('district') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Upazila / Thana</label>
                    <input type="text" name="upazila" value="{{ old('upazila') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                           placeholder="Mirpur">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"
                           placeholder="1216">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                <textarea name="address" rows="3"
                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition @error('address') border-red-300 bg-red-50 @enderror resize-none"
                          placeholder="House #, Road #, Area">{{ old('address') }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-4">
        <h3 class="text-lg font-bold text-gray-900">Payment Method <span class="text-red-500">*</span></h3>

        @error('payment_method') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm">
            <input type="radio" name="payment_method" value="cash_on_delivery" class="accent-indigo-600 w-4 h-4" checked>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Cash on Delivery</span>
                    <p class="text-xs text-gray-400">Pay when you receive</p>
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
                    <span class="text-sm font-semibold text-gray-900">SSLCOMMERZ</span>
                    <p class="text-xs text-gray-400">Coming Soon</p>
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
                    <p class="text-xs text-gray-400">Coming Soon</p>
                </div>
            </div>
        </label>

        <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 opacity-60 cursor-not-allowed">
            <input type="radio" name="payment_method" value="mobile_banking" class="accent-indigo-600 w-4 h-4" disabled>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Mobile Banking</span>
                    <p class="text-xs text-gray-400">Coming Soon</p>
                </div>
            </div>
        </label>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Order Notes <span class="text-gray-400">(Optional)</span></label>
        <textarea name="notes" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition resize-none" placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
    </div>
</form>