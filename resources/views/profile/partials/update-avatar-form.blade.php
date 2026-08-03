<section>
    <div class="gg-panel-head">
        <h2 class="gg-title">Profile Photo</h2>
        <p class="gg-sub">Your photo appears next to your name across your account.</p>
    </div>

    @php
        $avatar = $user->photo
            ? asset('storage/' . $user->photo)
            : 'https://api.dicebear.com/7.x/initials/svg?seed=' . rawurlencode($user->name) . '&backgroundColor=e4efe4';
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="relative">
                <img src="{{ $avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-4 border-[#e4efe4]" loading="lazy">
            </div>
            <div>
                <p class="font-bold text-[#173d2b]">{{ $user->name }}</p>
                <p class="text-sm text-[#5b6259]">{{ $user->email }}</p>
            </div>
        </div>

        <div class="sm:ml-auto flex flex-wrap gap-3">
            <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data"
                  x-data="{ uploading: false }" @submit="uploading = true">
                @csrf
                <label class="gg-btn cursor-pointer">
                    <span class="mr-1.5">📷</span>
                    <span x-text="uploading ? 'Uploading…' : 'Upload Photo'"></span>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="hidden" required @change="$el.closest('form').submit()">
                </label>
            </form>

            @if($user->photo)
                <form method="POST" action="{{ route('profile.avatar.remove') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="gg-btn-outline">Remove</button>
                </form>
            @endif
        </div>
    </div>

    @if($errors->has('photo'))
        <p class="gg-error mt-4">{{ $errors->first('photo') }}</p>
    @endif
</section>
