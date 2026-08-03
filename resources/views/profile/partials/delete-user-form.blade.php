<section class="space-y-6">
    <header class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-[#fef2f2] flex items-center justify-center">
            <svg class="w-5 h-5 text-[#b91c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-[#173d2b]">Delete Account</h2>
            <p class="text-sm text-[#5b6259]">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        </div>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                     class="!inline-flex !px-5 !py-2.5 !rounded-xl !text-sm">
        Delete Account
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6" x-data="{ deleting: false }">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Are you sure you want to delete your account?</h2>
            </div>

            <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>

            <div class="space-y-1.5 mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none transition @error('password', 'userDeletion') border-red-300 bg-red-50 @enderror">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition text-sm">
                    Cancel
                </button>
                <button type="submit" @click="deleting = true" :disabled="deleting"
                        class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition text-sm shadow-sm disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-2">
                    <template x-if="!deleting">
                        <span>Delete Account</span>
                    </template>
                    <template x-if="deleting">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                            Deleting...
                        </span>
                    </template>
                </button>
            </div>
        </form>
    </x-modal>
</section>
