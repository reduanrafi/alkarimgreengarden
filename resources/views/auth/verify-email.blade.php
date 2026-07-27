@extends('layouts.guest')

@section('title', __('Verify Email') . ' — ' . config('app.name'))

@section('heading', 'Verify Your Email')
@section('subheading', 'Thanks for signing up! Check your inbox for the verification link.')

@section('content')
    @if (session('status') == 'verification-link-sent')
        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="p-4 bg-[#66D9F1]/5 border border-[#66D9F1]/20 rounded-xl text-sm text-gray-600 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#4CC9F0] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <div>
            <p>Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
        </div>
    </div>

    <div class="flex items-center justify-between pt-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#66D9F1] to-[#4CC9F0] border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition font-medium">
                Log Out
            </button>
        </form>
    </div>
@endsection
