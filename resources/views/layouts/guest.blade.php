<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Green Garden'))</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95) translateY(12px); }
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
            animation: float 8s ease-in-out infinite;
        }
        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .animate-delay-5 { animation-delay: 0.5s; opacity: 0; }
    </style>
</head>
<body class="min-h-screen font-sans antialiased bg-gradient-to-br from-[#e4efe4] via-[#f7f9f6] to-[#dce8d5]">

    <div class="fixed inset-0 overflow-hidden pointer-events-none select-none">
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#6fae6e]/20 rounded-full blur-[110px] animate-float"></div>
        <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] bg-[#3f8a5c]/15 rounded-full blur-[120px] animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-[#6fae6e]/10 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-1/4 left-1/4 w-48 h-48 bg-[#1f5c3f]/10 rounded-full blur-[60px] animate-float" style="animation-delay: -5s;"></div>

        <svg class="absolute top-0 right-0 w-96 h-96 text-[#173d2b]/5" viewBox="0 0 400 400" fill="none" aria-hidden="true">
            <path d="M0 0 C 150 50, 250 150, 400 0" stroke="currentColor" stroke-width="2"/>
            <path d="M0 100 C 200 150, 300 50, 400 200" stroke="currentColor" stroke-width="1.5"/>
            <path d="M0 200 C 150 300, 250 200, 400 350" stroke="currentColor" stroke-width="1"/>
        </svg>
        <svg class="absolute bottom-0 left-0 w-80 h-80 text-[#3f8a5c]/10" viewBox="0 0 400 400" fill="none" aria-hidden="true">
            <path d="M400 400 C 250 350, 150 250, 0 400" stroke="currentColor" stroke-width="2"/>
            <path d="M400 300 C 200 250, 100 350, 0 200" stroke="currentColor" stroke-width="1.5"/>
        </svg>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md animate-fade-in-scale">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/90 overflow-hidden">
                <div class="bg-gradient-to-r from-[#173d2b] to-[#1f5c3f] px-8 py-8 text-center">
                    <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-3">
                        <span class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#a8d5b4]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c1.4 2.3 2.2 4.9 2.2 7.6 0 2.5-.8 4.7-2.2 6.4-1.4-1.7-2.2-3.9-2.2-6.4C9.8 6.9 10.6 4.3 12 2z"/><path d="M12 20.5c1.4-1.7 2.2-3.9 2.2-6.4 0-2.7-.8-5.3-2.2-7.6-1.4 2.3-2.2 4.9-2.2 7.6 0 2.5.8 4.7 2.2 6.4z"/></svg>
                        </span>
                        <span>
                            <span class="block font-['Fraunces'] text-2xl text-white leading-none">Green Garden</span>
                            <span class="block text-[11px] text-[#a8d5b4] font-semibold tracking-[0.2em] uppercase mt-1.5">Indoor &amp; Outdoor Plants</span>
                        </span>
                    </a>
                </div>

                <div class="p-8 sm:p-9">
                    <div class="text-center space-y-1 mb-6">
                        <h2 class="font-['Fraunces'] text-2xl text-[#173d2b]">@yield('heading', 'Welcome')</h2>
                        <p class="text-sm text-[#5b6259]">@yield('subheading', '')</p>
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
