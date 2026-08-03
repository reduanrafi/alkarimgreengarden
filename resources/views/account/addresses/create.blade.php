@extends('layouts.account')

@section('title', 'Add Address - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Address Book</p>
        <h1 class="gg-title">Add New Address 📍</h1>
        <p class="gg-sub">Fill in the details below to save a new shipping address.</p>
    </div>

    <div class="gg-panel">
        @include('account.addresses._form', ['submitLabel' => 'Save Address'])
    </div>
@endsection
