@props(['address'])

<div class="gg-panel gg-address-card">
    <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2.5">
            <span class="gg-address-icon">📍</span>
            <span class="font-bold text-[#173d2b]">{{ $address->label ?: 'Address' }}</span>
            @if($address->is_default)
                <span class="gg-badge-default">Default</span>
            @endif
        </div>
    </div>

    <div class="text-sm text-[#5b6259] space-y-1 mb-4">
        <p class="font-medium text-[#22281f]">{{ $address->recipient }}</p>
        @if($address->phone)
            <p>{{ $address->phone }}</p>
        @endif
        <p>{{ $address->address }}</p>
        <p>{{ collect([$address->city, $address->province])->filter()->implode(', ') }}</p>
        <p>{{ collect([$address->country, $address->postal_code])->filter()->implode(' - ') }}</p>
    </div>

    <div class="flex items-center gap-2 pt-3 border-t border-[#e6e9e2]">
        <a href="{{ route('account.addresses.edit', $address) }}" class="gg-chip-link">Edit</a>

        @if(!$address->is_default)
            <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="label" value="{{ $address->label }}">
                <input type="hidden" name="recipient" value="{{ $address->recipient }}">
                <input type="hidden" name="phone" value="{{ $address->phone }}">
                <input type="hidden" name="address" value="{{ $address->address }}">
                <input type="hidden" name="city" value="{{ $address->city }}">
                <input type="hidden" name="province" value="{{ $address->province }}">
                <input type="hidden" name="country" value="{{ $address->country }}">
                <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">
                <input type="hidden" name="is_default" value="1">
                <button type="submit" class="gg-chip-link">Set Default</button>
            </form>
        @endif

        <form method="POST" action="{{ route('account.addresses.destroy', $address) }}"
              onsubmit="return confirm('Delete this address?');" class="ml-auto">
            @csrf
            @method('DELETE')
            <button type="submit" class="gg-chip-link text-[#b91c1c] hover:!bg-[#fef2f2]">Delete</button>
        </form>
    </div>
</div>
