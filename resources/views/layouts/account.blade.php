@extends('layouts.app')

@section('content')
<div class="gg-account gg-container py-6 sm:py-10">
    <div class="gg-account-grid">
        @include('account.partials.sidebar')

        <main class="gg-account-main min-w-0">
            @yield('account-content')
        </main>
    </div>
</div>
@endsection
