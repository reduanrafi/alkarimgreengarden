<section>
    <div class="gg-panel-head">
        <h2 class="gg-title">Update Password</h2>
        <p class="gg-sub">Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="grid sm:grid-cols-3 gap-4" x-data="{ saving: false, saved: false }">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="gg-label">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="gg-input">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password" class="gg-label">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="gg-input">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="gg-label">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="gg-input">
        </div>

        <div class="sm:col-span-3 flex items-center gap-4 pt-2">
            <button type="submit" @click="saving = true; saved = false" :disabled="saving" class="gg-btn disabled:opacity-60 disabled:cursor-not-allowed">
                <span x-show="!saving">Save Password</span>
                <span x-show="saving" x-cloak>…</span>
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-[#1f5c3f] font-bold flex items-center gap-1">
                    ✓ Saved.
                </p>
            @endif
        </div>
    </form>
</section>
