<div class="space-y-4">
    {{-- Terms & Conditions --}}
    <label class="flex items-start gap-2.5 cursor-pointer select-none">
        <input type="checkbox" name="terms" form="checkout-form" value="1" required
               class="mt-0.5 w-4 h-4 rounded border-line text-brand-700 focus:ring-brand-500 @error('terms') border-red-400 @enderror">
        <span class="text-xs text-ink-soft leading-relaxed">
            I agree to the <span class="font-semibold text-ink">Terms &amp; Conditions</span> and confirm my order details are correct.
        </span>
    </label>
    @error('terms') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror

    {{-- Place Order --}}
    <button type="submit" form="checkout-form" :disabled="submitting"
            class="gg-btn w-full py-4 text-base inline-flex items-center justify-center gap-2">
        <template x-if="!submitting">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </template>
        <template x-if="submitting">
            <svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
        </template>
        <span x-text="submitting ? 'Processing Order…' : 'Place Order'"></span>
    </button>

    {{-- Trust Badges --}}
    <div class="flex items-center justify-center gap-6 pt-1 text-[10px] text-ink-soft uppercase tracking-wider">
        <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Secure Payment
        </span>
        <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Protected Data
        </span>
        <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Order Tracking
        </span>
    </div>
</div>
