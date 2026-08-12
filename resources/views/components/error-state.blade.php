@props([
    'title' => 'Something went wrong',
    'message' => 'We couldn\'t load this content. Please try again.',
    'retry' => true,
    'compact' => false,
])

<div class="{{ $compact ? 'text-center py-12' : 'text-center py-16 sm:py-20' }} bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-50 mb-5">
        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-900 mb-1.5">{{ $title }}</h3>
    <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">{{ $message }}</p>
    @if($retry)
        <button type="button"
                {{ $attributes }}
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#1f5c3f] text-white text-sm font-semibold rounded-lg hover:bg-[#173d2b] transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Retry
        </button>
    @endif
    {{ $slot }}
</div>
