@extends('layouts.account')

@section('title', 'Edit Address - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Address Book</p>
        <h1 class="gg-title">Edit Address 📍</h1>
        <p class="gg-sub">Update the details below to keep your address current.</p>
    </div>

    <div class="gg-panel">
        @include('account.addresses._form', ['address' => $address, 'submitLabel' => 'Update Address'])
    </div>
@endsection
