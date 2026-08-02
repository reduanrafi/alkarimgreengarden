@extends('layouts.app')

@section('title', 'Session Expired - ' . config('app.name'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-[10rem] sm:text-[12rem] font-bold leading-none mb-4 bg-gradient-to-r from-amber-400 to-amber-500 bg-clip-text text-transparent">419</div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 font-serif">Session Expired</h1>
        <p class="text-gray-400 mb-8 text-sm sm:text-base">Your session has expired. Please refresh the page and try again.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button type="button" onclick="window.location.reload()" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm inline-flex items-center gap-2 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh Page
            </button>
            <a href="{{ url()->previous() }}" class="px-8 py-3 border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition text-sm inline-flex items-center gap-2 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection