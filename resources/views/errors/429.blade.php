@extends('layouts.app')

@section('title', 'Too Many Requests - ' . config('app.name'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-[10rem] sm:text-[12rem] font-bold leading-none mb-4 bg-gradient-to-r from-purple-400 to-purple-500 bg-clip-text text-transparent">429</div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 font-serif">Too Many Requests</h1>
        <p class="text-gray-400 mb-8 text-sm sm:text-base">You've made too many requests. Please wait a moment before trying again.</p>
        <a href="{{ route('home') }}" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm inline-flex items-center gap-2 justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Go Home
        </a>
    </div>
</div>
@endsection