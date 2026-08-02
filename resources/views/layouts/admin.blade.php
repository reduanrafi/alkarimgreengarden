<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-950 text-gray-100 antialiased min-h-screen">
    {{-- Sidebar + overlay --}}
    @include('components.admin.sidebar')
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Main area --}}
    <div class="lg:pl-64 min-h-screen flex flex-col">
        @include('components.admin.topbar')

        {{-- Toast notifications --}}
        @if(session('success'))
            <div data-toast class="toast toast-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div data-toast class="toast toast-error">{{ session('error') }}</div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 p-4 lg:p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-800/50 px-6 py-4 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </div>

    {{-- Sidebar toggle script --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarToggle')?.addEventListener('click', toggleSidebar);
        });
        function confirmAction(formId, title, text, confirmText, confirmColor) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor || '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText || 'Yes, proceed',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
