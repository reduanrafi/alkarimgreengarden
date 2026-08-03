<section>
    <div class="gg-panel-head">
        <h2 class="gg-title">Profile Information</h2>
        <p class="gg-sub">Update your account's profile information, phone, and email address.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="grid sm:grid-cols-2 gap-4" x-data="{ saving: false, saved: false }">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="gg-label">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="gg-input">
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="phone" class="gg-label">Phone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" autocomplete="tel" class="gg-input" placeholder="+880 1XXX-XXXXXX">
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div class="sm:col-span-2">
            <label for="email" class="gg-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="gg-input">
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-[#fef3c7] border border-[#fde68a] rounded-xl text-sm text-[#b45309]">
                    <p>
                        Your email address is unverified.
                        <button form="send-verification" class="underline font-bold hover:text-[#92400e]">
                            Click here to re-send the verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-medium text-[#1f5c3f]">A new verification link has been sent to your email address.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="sm:col-span-2 flex items-center gap-4 pt-2">
            <button type="submit" @click="saving = true; saved = false" :disabled="saving" class="gg-btn disabled:opacity-60 disabled:cursor-not-allowed">
                <span x-show="!saving">Save Changes</span>
                <span x-show="saving" x-cloak>…</span>
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-[#1f5c3f] font-bold flex items-center gap-1">
                    ✓ Saved.
                </p>
            @endif
        </div>
    </form>
</section>
