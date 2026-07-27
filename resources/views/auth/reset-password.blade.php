@extends('layouts.guest')

@section('title', __('Reset Password') . ' — ' . config('app.name'))

@section('heading', 'Reset Password')
@section('subheading', 'Enter your new password below.')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5" x-data="{ loading: false }">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="space-y-1.5">
            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
            <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="you@example.com"
                   class="w-full bg-white/60 border border-gray-200 rounded-xl py-3 px-4 text-gray-800 placeholder-gray-400 text-sm outline-none transition-all duration-200 focus:border-[#66D9F1] focus:ring-2 focus:ring-[#66D9F1]/20 @error('email') border-red-300 bg-red-50 @enderror">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="space-y-1.5">
            <label for="password" class="block text-sm font-medium text-gray-600">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Enter new password"
                   class="w-full bg-white/60 border border-gray-200 rounded-xl py-3 px-4 text-gray-800 placeholder-gray-400 text-sm outline-none transition-all duration-200 focus:border-[#66D9F1] focus:ring-2 focus:ring-[#66D9F1]/20 @error('password') border-red-300 bg-red-50 @enderror">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat new password"
                   class="w-full bg-white/60 border border-gray-200 rounded-xl py-3 px-4 text-gray-800 placeholder-gray-400 text-sm outline-none transition-all duration-200 focus:border-[#66D9F1] focus:ring-2 focus:ring-[#66D9F1]/20">
        </div>

        <button type="submit" @click="loading = true" :disabled="loading"
                class="w-full py-3 px-6 rounded-xl text-white font-semibold text-sm bg-gradient-to-r from-[#66D9F1] to-[#4CC9F0] shadow-lg shadow-[#66D9F1]/20 hover:shadow-xl hover:shadow-[#66D9F1]/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center justify-center gap-2">
            <template x-if="!loading">
                <span>Reset Password</span>
            </template>
            <template x-if="loading">
                <span class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    Resetting...
                </span>
            </template>
        </button>
    </form>
@endsection
