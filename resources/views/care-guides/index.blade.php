@extends('layouts.app')

@section('title', 'Plant Care Guides - ' . config('app.name'))
@section('meta_description', 'Practical plant care guides covering watering, light, soil, repotting and troubleshooting to help every plant thrive.')

@section('content')
<section class="gg-page-hero">
    <div class="gg-container">
        <div class="gg-crumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span>Plant Care Guides</span>
        </div>
        <span class="eyebrow mt-4 block">Learn & Grow</span>
        <h1>Plant Care Guides</h1>
        <p>Hands-on guides to help every plant thrive — from watering and light to repotting and common problems.</p>
    </div>
</section>

<section class="py-14">
    <div class="gg-container">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <form action="{{ route('care.index') }}" method="GET" class="relative sm:w-80">
                <input type="search" name="q" value="{{ $search }}"
                       placeholder="Search guides…"
                       class="gg-input !rounded-full !py-3 !pr-20">
                <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 gg-btn-primary !px-4 !py-2 text-sm">
                    Search
                </button>
            </form>

            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('care.index', ['q' => $search ?: null]) }}"
                       class="gg-faq-chip {{ $activeCategory === '' ? 'active' : '' }}">All</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('care.index', ['category' => $category, 'q' => $search ?: null]) }}"
                           class="gg-faq-chip {{ $activeCategory === $category ? 'active' : '' }}">{{ $category }}</a>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($guides->isEmpty())
            <div class="gg-panel text-center py-14">
                <div class="text-5xl mb-4">🌱</div>
                <h2 class="gg-title text-lg mb-2">No guides found</h2>
                <p class="text-ink-soft text-sm mb-6">We are still writing this guide. Check back soon or ask us a question.</p>
                <a href="{{ route('contact') }}" class="gg-btn">Ask Us</a>
            </div>
        @else
            <div class="gg-care-grid">
                @foreach ($guides as $guide)
                    <x-care-card :guide="$guide" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $guides->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
