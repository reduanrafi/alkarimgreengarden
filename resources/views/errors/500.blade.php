@extends('layouts.app')

@section('title', 'Server Error - ' . config('app.name'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-[10rem] sm:text-[12rem] font-bold leading-none mb-4 text-gray-200">500</div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 font-serif">Something Went Wrong</h1>
        <p class="text-gray-400 mb-8 text-sm sm:text-base">An unexpected error occurred. Our team has been notified and is working on it.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm inline-flex items-center gap-2 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Go Home
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-3 border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition text-sm inline-flex items-center gap-2 justify-center">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection