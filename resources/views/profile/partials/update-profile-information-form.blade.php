<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-[#66D9F1]/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-[#4CC9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
            <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5" x-data="{ saving: false, saved: false }">
        @csrf
        @method('patch')

        <div class="space-y-1.5">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#66D9F1]/20 focus:border-[#66D9F1] outline-none transition @error('name') border-red-300 bg-red-50 @enderror">
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1.5">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#66D9F1]/20 focus:border-[#66D9F1] outline-none transition @error('email') border-red-300 bg-red-50 @enderror">
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-700">
                        Your email address is unverified.
                        <button form="send-verification" class="underline text-amber-600 hover:text-amber-800 font-medium">
                            Click here to re-send the verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-sm font-medium text-emerald-600">A new verification link has been sent to your email address.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" @click="saving = true; saved = false" :disabled="saving"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#66D9F1] to-[#4CC9F0] border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                <template x-if="!saving">
                    <span>Save Changes</span>
                </template>
                <template x-if="saving">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        Saving...
                    </span>
                </template>
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>
