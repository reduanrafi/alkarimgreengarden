@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))
@section('meta_description', 'Discover the story, mission and values behind ' . config('app.name') . ' — a garden shop built on quality plants and honest care.')

@php
    $story = setting('about_story', "Founded with a love for everything green, we started as a small plant stall and grew into a curated garden shop. Today we help hundreds of homes grow healthier, happier plants.\n\nEvery plant we sell is grown and inspected with care. We pick varieties that thrive in real homes, and we stand behind them long after they leave our shelves — with honest advice on watering, light and repotting whenever you need it.");
    $storyParagraphs = collect(preg_split('/\r\n|\r|\n/', trim($story)))->filter(fn ($p) => trim($p) !== '')->values();
    $mission = setting('about_mission', 'To make beautiful, healthy plants accessible to everyone — and to give you the knowledge to keep them thriving.');
    $vision = setting('about_vision', 'To become the most trusted plant destination in the region, known for quality stock, honest advice and a community of growing gardeners.');
    $values = json_decode(setting('about_values', '[]'), true) ?: [];
    $stats = json_decode(setting('about_stats', '[]'), true) ?: [];
    $achievements = json_decode(setting('about_achievements', '[]'), true) ?: [];
    $cover = setting('about_cover');
@endphp

@section('content')
<section class="gg-page-hero">
    <div class="gg-container">
        <div class="gg-crumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span>About Us</span>
        </div>
        <span class="eyebrow mt-4 block">Our Story</span>
        <h1>About {{ setting('website_name', config('app.name')) }}</h1>
        <p>Discover who we are, what drives us, and why our plants leave the greenhouse with a little extra love.</p>
    </div>
</section>

<section class="py-14">
    <div class="gg-container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-16">
            <div>
                @if ($cover)
                    <img src="{{ asset('storage/' . $cover) }}" alt="About {{ setting('website_name', config('app.name')) }}" class="rounded-2xl border border-line w-full object-cover">
                @else
                    <div class="rounded-2xl border border-line bg-gradient-to-br from-green-100 to-cream aspect-[4/3] flex items-center justify-center text-7xl">🌿</div>
                @endif
            </div>
            <div class="gg-about-story">
                <span class="gg-eyebrow mb-3">Our Story</span>
                <h2 class="gg-title !text-3xl mb-4">From seed to your shelf</h2>
                @foreach ($storyParagraphs as $paragraph)
                    <p>{!! nl2br(e($paragraph)) !!}</p>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            <div class="gg-value-card">
                <div class="gg-contact-ico">🎯</div>
                <h3>Our Mission</h3>
                <p>{{ $mission }}</p>
            </div>
            <div class="gg-value-card">
                <div class="gg-contact-ico">👁️</div>
                <h3>Our Vision</h3>
                <p>{{ $vision }}</p>
            </div>
        </div>

        @if ($values)
            <div class="mb-16">
                <div class="gg-section-head">
                    <div>
                        <span class="gg-eyebrow">What We Believe</span>
                        <h2>Our Values</h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($values as $value)
                        <div class="gg-value-card">
                            <div class="gg-contact-ico">{{ $value['emoji'] ?? '🌿' }}</div>
                            <h3>{{ $value['title'] ?? '' }}</h3>
                            <p>{{ $value['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($stats)
            <div class="mb-16">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($stats as $stat)
                        <div class="gg-stat-card">
                            <div class="gg-stat-value">{{ $stat['value'] ?? $stat['emoji'] ?? '' }}</div>
                            <div class="gg-stat-label">{{ $stat['label'] ?? $stat['title'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($achievements)
            <div class="mb-16">
                <div class="gg-section-head">
                    <div>
                        <span class="gg-eyebrow">Milestones</span>
                        <h2>What We've Achieved</h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($achievements as $achievement)
                        <div class="gg-value-card">
                            <div class="gg-contact-ico">{{ $achievement['emoji'] ?? '🏆' }}</div>
                            <h3>{{ $achievement['title'] ?? '' }}</h3>
                            <p>{{ $achievement['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="gg-panel text-center p-10">
            <h2 class="gg-title !text-2xl mb-2">Ready to grow with us?</h2>
            <p class="text-ink-soft text-sm mb-6">Browse the shop or ask us anything — we love talking plants.</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('products.index') }}" class="gg-btn">Shop the Collection</a>
                <a href="{{ route('contact') }}" class="gg-btn-outline">Get in Touch</a>
            </div>
        </div>
    </div>
</section>
@endsection
