<div class="bg-white rounded-2xl border border-line shadow-sm p-6 sm:p-8 space-y-4">
    <div class="flex items-center gap-3">
        <div class="gg-icon-tile">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-ink">Payment Method</h2>
            <p class="text-xs text-ink-soft">Select your payment option</p>
        </div>
    </div>

    @error('payment_method') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror

    <label class="gg-radio-card">
        <input type="radio" name="payment_method" value="cash_on_delivery" x-model="paymentMethod" checked>
        <div class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 rounded-lg bg-brand-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <span class="text-sm font-semibold text-ink">Cash on Delivery</span>
                <p class="text-xs text-ink-soft">Pay when you receive your order</p>
            </div>
        </div>
    </label>

    <label class="gg-radio-card gg-radio-disabled">
        <input type="radio" name="payment_method" value="sslcommerz" disabled>
        <div class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <span class="text-sm font-semibold text-ink">SSLCommerz</span>
                <p class="text-xs text-amber-600 font-medium">Coming Soon — gateway not configured</p>
            </div>
        </div>
    </label>

    <label class="gg-radio-card gg-radio-disabled">
        <input type="radio" name="payment_method" value="card" disabled>
        <div class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <span class="text-sm font-semibold text-ink">Card Payment</span>
                <p class="text-xs text-amber-600 font-medium">Coming Soon — gateway not configured</p>
            </div>
        </div>
    </label>
</div>
