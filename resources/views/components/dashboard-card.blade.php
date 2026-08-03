@props([
    'label' => '',
    'value' => '',
    'emoji' => '🌿',
    'href' => null,
])

<div class="gg-dash-card">
    @if($href)
        <a href="{{ $href }}" class="block">
            <div class="gg-dash-card-top">
                <span class="gg-dash-emoji">{{ $emoji }}</span>
                <svg class="w-4 h-4 text-[#6fae6e] transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="gg-dash-value">{{ $value }}</p>
            <p class="gg-dash-label">{{ $label }}</p>
        </a>
    @else
        <div class="gg-dash-card-top">
            <span class="gg-dash-emoji">{{ $emoji }}</span>
        </div>
        <p class="gg-dash-value">{{ $value }}</p>
        <p class="gg-dash-label">{{ $label }}</p>
    @endif
</div>
