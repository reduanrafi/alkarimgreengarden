@php
    $ggSiteName = setting('website_name', config('app.name'));
    $ggOgImage = setting('og_image');
    $ggFavicon = setting('favicon');
    $ggSocials = array_values(array_filter([
        setting('facebook_url'),
        setting('instagram_url'),
        setting('twitter_url'),
        setting('youtube_url'),
        setting('linkedin_url'),
    ]));
    $ggOrganization = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $ggSiteName,
        'url' => url('/'),
        'email' => setting('website_email'),
        'telephone' => setting('website_phone'),
        'logo' => setting('logo') ? asset('storage/' . setting('logo')) : null,
        'image' => setting('logo') ? asset('storage/' . setting('logo')) : null,
        'sameAs' => $ggSocials,
    ];
    $ggOrganization = array_filter($ggOrganization, fn ($v) => ! is_null($v) && $v !== '');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', setting('meta_description', config('app.name') . ' - Plants, planters and garden essentials for every space.'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('meta_keywords', 'plants, planters, gardening, garden, shop online'))">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    @yield('meta')

    <meta property="og:title" content="@yield('title', $ggSiteName)">
    <meta property="og:description" content="@yield('meta_description', setting('meta_description', config('app.name') . ' - Plants, planters and garden essentials.'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $ggSiteName }}">
    @if ($ggOgImage)
        <meta property="og:image" content="{{ asset('storage/' . $ggOgImage) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $ggSiteName)">
    <meta name="twitter:description" content="@yield('meta_description', setting('meta_description', config('app.name') . ' - Plants, planters and garden essentials.'))">
    @if ($ggOgImage)
        <meta name="twitter:image" content="{{ asset('storage/' . $ggOgImage) }}">
    @endif
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    @if ($ggFavicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $ggFavicon) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif

    <title>@yield('title', $ggSiteName)</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ $ggSiteName }}",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ route('products.index') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <script type="application/ld+json">
    {!! json_encode($ggOrganization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @stack('structured_data')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-cream">
        @include('layouts.navigation')

        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-emerald-50 border-b border-emerald-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-emerald-800">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @isset($header)
            <header class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        @hasSection('header')
            <header class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <x-footer />
    </div>

    @stack('scripts')
</body>
</html>
