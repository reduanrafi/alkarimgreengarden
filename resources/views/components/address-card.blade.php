@php
    $user = auth()->user();
    $hasSaved = $user && ($user->address || $user->phone || $user->city);
@endphp

@if($user && $hasSaved)
    <div class="bg-white rounded-2xl border border-line shadow-sm p-5 sm:p-6">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="gg-icon-tile shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-ink">Saved Address</p>
                    <p class="text-xs text-ink-soft mt-0.5">We've pre-filled your delivery details from your profile.</p>
                    <div class="text-sm text-ink mt-2.5 space-y-0.5">
                        <p class="font-medium">{{ $user->name }}</p>
                        @if($user->phone) <p class="text-ink-soft">{{ $user->phone }}</p> @endif
                        @if($user->address)
                            <p class="text-ink-soft">{{ $user->address }}{{ $user->city ? ', ' . $user->city : '' }}{{ $user->postal_code ? ' - ' . $user->postal_code : '' }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <a href="#shipping-address"
               class="shrink-0 text-sm font-semibold text-brand-700 hover:text-brand-900 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl border border-line shadow-sm p-5 sm:p-6">
        <div class="flex items-center gap-3">
            <div class="gg-icon-tile shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-ink">New Delivery Address</p>
                <p class="text-xs text-ink-soft">Fill in your shipping details below.</p>
            </div>
        </div>
    </div>
@endif
