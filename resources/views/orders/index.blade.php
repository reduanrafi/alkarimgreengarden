@extends('layouts.account')

@section('title', 'My Orders - ' . config('app.name'))

@section('account-content')
    <div class="gg-account-head">
        <p class="gg-eyebrow">Order History</p>
        <h1 class="gg-title">My Orders 📦</h1>
        <p class="gg-sub">Track, view, and download invoices for all your orders.</p>
    </div>

    @if(session('success'))
        <div class="gg-alert gg-alert-success mb-6">{{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)
        <div class="mb-4">
            <p class="text-sm text-[#5b6259]">Total <strong class="text-[#173d2b]">{{ $orders->total() }}</strong> order(s)</p>
        </div>

        <x-order-table :orders="$orders" />

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <x-empty-state
            icon="orders"
            title="No orders yet"
            message="You haven't placed any orders yet. Start shopping and your orders will appear here."
            :action="route('products.index')"
            actionText="Start Shopping"
        />
    @endif
@endsection
