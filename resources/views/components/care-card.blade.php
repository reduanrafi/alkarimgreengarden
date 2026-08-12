@props(['guide'])

<article class="gg-care-card">
    <a href="{{ route('care.show', $guide->slug) }}" class="gg-care-media">
        @if ($guide->cover_image)
            <img src="{{ asset('storage/' . $guide->cover_image) }}" alt="{{ $guide->title }}" loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-5xl">🌱</div>
        @endif
        @if ($guide->category)
            <span class="gg-care-cat">{{ $guide->category }}</span>
        @endif
    </a>
    <div class="gg-care-body">
        <a href="{{ route('care.show', $guide->slug) }}">
            <h3>{{ $guide->title }}</h3>
        </a>
        <p>{{ $guide->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($guide->content), 120) }}</p>
        <div class="gg-care-foot">
            <span class="text-xs text-ink-soft font-medium">{{ $guide->updated_at->format('M j, Y') }}</span>
            <a href="{{ route('care.show', $guide->slug) }}" class="gg-view-all">Read Guide <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</article>
