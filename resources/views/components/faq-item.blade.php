@props(['faq', 'index' => 0, 'emoji' => '🌿'])

<div class="gg-faq-item" x-data="{ open: false }" :class="{ 'open': open }">
    <button type="button" class="gg-faq-q" @click="open = !open" :aria-expanded="open.toString()">
        <span class="faq-emoji">{{ $emoji }}</span>
        <span class="faq-text">{{ $faq->question }}</span>
        <span class="gg-faq-chevron">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </span>
    </button>
    <div class="gg-faq-a">
        {!! nl2br(e($faq->answer)) !!}
    </div>
</div>
