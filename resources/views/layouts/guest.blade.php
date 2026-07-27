<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Art Crafts & Fashion'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=great-vibes&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.92) translateY(12px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-24px); }
        }
        .animate-fade-in-scale {
            animation: fadeInScale 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-float {
            animation: float 7s ease-in-out infinite;
        }
        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .animate-delay-5 { animation-delay: 0.5s; opacity: 0; }
    </style>
</head>
<body class="min-h-screen font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-cyan-50">

    <div class="fixed inset-0 overflow-hidden pointer-events-none select-none">
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#66D9F1]/20 rounded-full blur-[100px] animate-float"></div>
        <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] bg-[#8EE7F5]/15 rounded-full blur-[120px] animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-[#66D9F1]/10 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-1/4 left-1/4 w-48 h-48 bg-[#4CC9F0]/10 rounded-full blur-[60px] animate-float" style="animation-delay: -5s;"></div>

        <svg class="absolute top-0 right-0 w-96 h-96 text-[#66D9F1]/5" viewBox="0 0 400 400" fill="none" aria-hidden="true">
            <path d="M0 0 C 150 50, 250 150, 400 0" stroke="currentColor" stroke-width="2"/>
            <path d="M0 100 C 200 150, 300 50, 400 200" stroke="currentColor" stroke-width="1.5"/>
            <path d="M0 200 C 150 300, 250 200, 400 350" stroke="currentColor" stroke-width="1"/>
        </svg>
        <svg class="absolute bottom-0 left-0 w-80 h-80 text-[#8EE7F5]/10" viewBox="0 0 400 400" fill="none" aria-hidden="true">
            <path d="M400 400 C 250 350, 150 250, 0 400" stroke="currentColor" stroke-width="2"/>
            <path d="M400 300 C 200 250, 100 350, 0 200" stroke="currentColor" stroke-width="1.5"/>
        </svg>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md animate-fade-in-scale">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg shadow-[#66D9F1]/10 border border-white/80 p-8 sm:p-10">
                <div class="space-y-8">
                    <div class="text-center space-y-2">
                        <a href="{{ url('/') }}">
                            <h1 class="font-['Great_Vibes'] text-5xl sm:text-6xl text-gray-800 leading-none">
                                Fashion
                            </h1>
                        </a>
                        <p class="text-xs text-gray-400 font-medium tracking-[0.25em] uppercase">
                            Art Crafts &amp; Fashion
                        </p>
                    </div>

                    <div class="text-center space-y-1">
                        <h2 class="text-xl font-semibold text-gray-800">@yield('heading', 'Welcome')</h2>
                        <p class="text-sm text-gray-400">@yield('subheading', '')</p>
                    </div>

                    <x-auth-session-status class="text-center !text-sm !font-medium" :status="session('status')" />

                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
