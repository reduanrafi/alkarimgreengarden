@props(['address' => null, 'submitLabel' => 'Save Address'])

<form method="POST" action="{{ $address ? route('account.addresses.update', $address) : route('account.addresses.store') }}">
    @csrf
    @if($address)
        @method('PUT')
    @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="gg-label">Label <span class="text-[#8a938a]">(e.g. Home, Office)</span></label>
            <input type="text" name="label" class="gg-input" placeholder="Home"
                   value="{{ old('label', $address->label ?? '') }}">
            @error('label')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="gg-label">Recipient *</label>
            <input type="text" name="recipient" class="gg-input" placeholder="Full name"
                   value="{{ old('recipient', $address->recipient ?? auth()->user()->name) }}">
            @error('recipient')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="gg-label">Phone</label>
            <input type="text" name="phone" class="gg-input" placeholder="+880 1XXX-XXXXXX"
                   value="{{ old('phone', $address->phone ?? auth()->user()->phone) }}">
            @error('phone')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div class="sm:col-span-2">
            <label class="gg-label">Street Address *</label>
            <textarea name="address" rows="2" class="gg-input" placeholder="House, road, area">{{ old('address', $address->address ?? '') }}</textarea>
            @error('address')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="gg-label">City</label>
            <input type="text" name="city" class="gg-input" value="{{ old('city', $address->city ?? '') }}">
            @error('city')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="gg-label">State / Province</label>
            <input type="text" name="province" class="gg-input" value="{{ old('province', $address->province ?? '') }}">
            @error('province')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="gg-label">Country</label>
            <input type="text" name="country" class="gg-input" value="{{ old('country', $address->country ?? '') }}">
            @error('country')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="gg-label">Postal Code</label>
            <input type="text" name="postal_code" class="gg-input" value="{{ old('postal_code', $address->postal_code ?? '') }}">
            @error('postal_code')<p class="gg-error">{{ $message }}</p>@enderror
        </div>

        <label class="sm:col-span-2 flex items-center gap-2.5 text-sm text-[#22281f] cursor-pointer select-none">
            <input type="checkbox" name="is_default" value="1"
                   @checked(old('is_default', $address->is_default ?? false))
                   class="w-4 h-4 rounded border-[#d5dacf] text-[#3f8a5c] focus:ring-[#6fae6e]">
            <span>Set as my default shipping address</span>
        </label>
    </div>

    <div class="flex flex-wrap gap-3 mt-6">
        <button type="submit" class="gg-btn">{{ $submitLabel }}</button>
        <a href="{{ route('account.addresses.index') }}" class="gg-btn-outline">Cancel</a>
    </div>
</form>
