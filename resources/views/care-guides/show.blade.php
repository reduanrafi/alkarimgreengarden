@extends('layouts.app')

@section('title', ($guide->meta_title ?: $guide->title . ' - Plant Care Guides') . ' - ' . config('app.name'))
@section('meta_description', $guide->meta_description ?: ($guide->excerpt ?: 'Practical plant care guide from ' . config('app.name') . '.'))

@section('content')
<section class="py-12">
    <div class="gg-container max-w-4xl">
        <div class="gg-pdp-breadcrumb mb-6">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('care.index') }}">Plant Care</a>
            @if ($guide->category)
                <span class="sep">/</span>
                <a href="{{ route('care.index', ['category' => $guide->category]) }}">{{ $guide->category }}</a>
            @endif
            <span class="sep">/</span>
            <span class="current">{{ $guide->title }}</span>
        </div>

        <article>
            <span class="eyebrow block mb-3">Plant Care Guide</span>
            <h1 class="gg-pdp-title text-3xl lg:text-4xl mb-4">{{ $guide->title }}</h1>

            <div class="flex items-center gap-4 mb-8 text-sm text-ink-soft">
                <span>Updated {{ $guide->updated_at->format('M j, Y') }}</span>
                @if ($guide->category)
                    <a href="{{ route('care.index', ['category' => $guide->category]) }}" class="gg-pdp-pill">{{ $guide->category }}</a>
                @endif
            </div>

            @if ($guide->cover_image)
                <div class="rounded-2xl overflow-hidden border border-line mb-8">
                    <img src="{{ asset('storage/' . $guide->cover_image) }}" alt="{{ $guide->title }}" class="w-full aspect-[16/7] object-cover">
                </div>
            @endif

            <div class="gg-panel !p-6 lg:!p-10">
                @if ($guide->excerpt)
                    <p class="text-lg text-ink-soft leading-relaxed mb-6 font-medium">{{ $guide->excerpt }}</p>
                @endif
                <div class="gg-care-content">
                    {!! $guide->content !!}
                </div>
            </div>
        </article>

        @if ($related->isNotEmpty())
            <div class="mt-14">
                <div class="gg-section-head">
                    <div>
                        <span class="gg-eyebrow">Keep Learning</span>
                        <h2>Related Guides</h2>
                    </div>
                    <a href="{{ route('care.index') }}" class="gg-view-all">View All Guides &rarr;</a>
                </div>
                <div class="gg-care-grid">
                    @foreach ($related as $relatedGuide)
                        <x-care-card :guide="$relatedGuide" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
