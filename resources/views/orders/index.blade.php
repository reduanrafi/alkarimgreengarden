@extends('layouts.app')

@section('title', 'My Orders - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ loaded: false, init() { setTimeout(() => this.loaded = true, 300); } }">
    <div class="mb-8">
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:text-indigo-700 transition inline-flex items-center gap-1.5 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Home
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-serif">My Orders</h1>
            <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium hidden sm:inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Shop More
            </a>
        </div>
    </div>

    {{-- Skeleton Loading --}}
    <div x-show="!loaded" x-cloak class="space-y-4">
        <x-skeletons.order-card :count="3" />
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3.5 text-sm mb-8 flex items-center gap-2" x-show="loaded">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div x-show="loaded">
    @if($orders->count() > 0)
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-gray-500">Total <strong>{{ $orders->total() }}</strong> order(s)</p>
            </div>

            @foreach($orders as $order)
                <x-order-card :order="$order" />
            @endforeach
        </div>

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
    </div>
</div>
@endsection
