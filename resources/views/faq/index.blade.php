@extends('layouts.app')

@section('title', 'FAQ - ' . config('app.name'))
@section('meta_description', 'Frequently asked questions about ordering, shipping, plant care, returns and more from ' . config('app.name') . '.')

@section('content')
<section class="gg-page-hero">
    <div class="gg-container">
        <div class="gg-crumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span>FAQ</span>
        </div>
        <span class="eyebrow mt-4 block">Help Center</span>
        <h1>Frequently Asked Questions</h1>
        <p>Everything you need to know about shopping with us, plant care, shipping and returns. Can't find what you're looking for? <a href="{{ route('contact') }}" class="text-white underline decoration-green-300 underline-offset-2 hover:opacity-85">Get in touch</a>.</p>
    </div>
</section>

<section class="py-14">
    <div class="gg-container">
        <div class="max-w-3xl mx-auto mb-10">
            <form action="{{ route('faq.index') }}" method="GET" class="relative">
                <input type="search" name="q" value="{{ $search }}"
                       placeholder="Search questions… e.g. shipping, returns, watering"
                       class="gg-input !rounded-full !py-3.5 !pr-24">
                <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 gg-btn-primary !px-5 !py-2.5 text-sm">
                    Search
                </button>
            </form>
        </div>

        @if ($categories->isNotEmpty())
            <div class="gg-faq-filters">
                <a href="{{ route('faq.index', ['q' => $search ?: null]) }}"
                   class="gg-faq-chip {{ $activeCategory === '' ? 'active' : '' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('faq.index', ['category' => $category, 'q' => $search ?: null]) }}"
                       class="gg-faq-chip {{ $activeCategory === $category ? 'active' : '' }}">{{ $category }}</a>
                @endforeach
            </div>
        @endif

        <div class="gg-faq-wrap">
            @forelse ($faqs as $faq)
                <x-faq-item :faq="$faq" :index="$loop->index" />
            @empty
                <div class="gg-panel text-center py-14">
                    <div class="text-5xl mb-4">🪴</div>
                    <h2 class="gg-title text-lg mb-2">No questions found</h2>
                    <p class="text-ink-soft text-sm mb-6">Try a different search term, or reach out and we will help you directly.</p>
                    <a href="{{ route('contact') }}" class="gg-btn">Contact Us</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
