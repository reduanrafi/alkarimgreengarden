@extends('layouts.account')

@section('title', 'My Addresses - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Address Book</p>
        <h1 class="gg-title">My Addresses 📍</h1>
        <p class="gg-sub">Keep your shipping details handy for a faster checkout.</p>
    </div>

    @if(session('success'))
        <div class="gg-alert gg-alert-success mb-6">{{ session('success') }}</div>
    @endif

    <div class="flex justify-end mb-6">
        <a href="{{ route('account.addresses.create') }}" class="gg-btn">
            <span class="mr-1.5">＋</span> Add New Address
        </a>
    </div>

    @if($addresses->count() > 0)
        <div class="gg-address-grid">
            @foreach($addresses as $address)
                <x-account.address-card :address="$address" />
            @endforeach
        </div>
    @else
        <x-empty-state
            icon="location"
            title="No addresses yet"
            message="Add a shipping address so checkout is quick and easy."
            :action="route('account.addresses.create')"
            actionText="Add Your First Address"
        />
    @endif
@endsection
