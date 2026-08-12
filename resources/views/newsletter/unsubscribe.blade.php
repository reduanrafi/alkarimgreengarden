@extends('layouts.app')

@section('title', 'Unsubscribe from Newsletter')

@section('content')
<section class="py-16">
    <div class="gg-container">
        <div class="gg-panel max-w-xl mx-auto text-center p-8 lg:p-12">
            <div class="text-5xl mb-4">🌿</div>
            <h1 class="gg-display text-2xl mb-3">Unsubscribe from our newsletter</h1>
            <p class="text-ink-soft mb-8">
                You are subscribed with <strong>{{ $subscriber->email }}</strong>.
                We are sorry to see you go, but you can leave anytime.
            </p>

            @if (session('status'))
                <div class="gg-alert gg-alert-success mb-6">{{ session('status') }}</div>
            @endif

            @if ($subscriber->is_active)
                <form method="POST" action="{{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}">
                    @csrf
                    <button type="submit" class="gg-btn-primary w-full">
                        Unsubscribe me
                    </button>
                </form>
                <p class="text-sm text-ink-soft mt-4">
                    Changed your mind? You can simply close this page.
                </p>
            @else
                <form method="POST" action="{{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}">
                    @csrf
                    <button type="submit" class="gg-btn-primary w-full">
                        Re-subscribe me
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
