@extends('layouts.account')

@section('title', 'Profile Settings - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Account</p>
        <h1 class="gg-title">Profile Settings 👤</h1>
        <p class="gg-sub">Manage your personal details, photo, and security.</p>
    </div>

    <div class="space-y-6">
        <div class="gg-panel">
            @include('profile.partials.update-avatar-form')
        </div>

        <div class="gg-panel">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="gg-panel">
            @include('profile.partials.update-password-form')
        </div>

        <div class="gg-panel !border-[#f3d0d0]">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
