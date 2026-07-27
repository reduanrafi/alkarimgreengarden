<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-[#66D9F1]/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-[#4CC9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Update Password</h2>
            <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5" x-data="{ saving: false, saved: false }">
        @csrf
        @method('put')

        <div class="space-y-1.5">
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#66D9F1]/20 focus:border-[#66D9F1] outline-none transition @error('current_password', 'updatePassword') border-red-300 bg-red-50 @enderror">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#66D9F1]/20 focus:border-[#66D9F1] outline-none transition @error('password', 'updatePassword') border-red-300 bg-red-50 @enderror">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#66D9F1]/20 focus:border-[#66D9F1] outline-none transition">
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" @click="saving = true; saved = false" :disabled="saving"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#66D9F1] to-[#4CC9F0] border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                <template x-if="!saving">
                    <span>Save Password</span>
                </template>
                <template x-if="saving">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        Saving...
                    </span>
                </template>
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>
