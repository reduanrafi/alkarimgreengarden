@extends('layouts.admin')
@section('title', $customer->name . ' — Customer Profile')
@section('content')

{{-- Action Bar --}}
<div class="flex items-center justify-end gap-2 mb-6">
    <a href="{{ route('admin.customers.edit', $customer) }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit
    </a>

    @if($customer->status === 'active')
        <form method="POST" action="{{ route('admin.customers.block', $customer) }}" class="inline" id="show-block-form">
            @csrf @method('PATCH')
            <button type="button" onclick="confirmAction('show-block-form', 'Block {{ $customer->name }}?', 'They will be unable to login or place orders.', 'Yes, block', '#ef4444')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Block
            </button>
        </form>
        <form method="POST" action="{{ route('admin.customers.deactivate', $customer) }}" class="inline" id="show-deactivate-form">
            @csrf @method('PATCH')
            <button type="button" onclick="confirmAction('show-deactivate-form', 'Deactivate {{ $customer->name }}?', 'Their account status will be set to pending.', 'Yes, deactivate', '#f59e0b')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-gray-500/10 text-gray-400 hover:bg-gray-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Deactivate
            </button>
        </form>
    @elseif($customer->status === 'blocked')
        <form method="POST" action="{{ route('admin.customers.unblock', $customer) }}" class="inline" id="show-unblock-form">
            @csrf @method('PATCH')
            <button type="button" onclick="confirmAction('show-unblock-form', 'Unblock {{ $customer->name }}?', 'They will regain access to their account.', 'Yes, unblock', '#10b981')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
                Unblock
            </button>
        </form>
    @elseif($customer->status === 'pending')
        <form method="POST" action="{{ route('admin.customers.activate', $customer) }}" class="inline" id="show-activate-form">
            @csrf @method('PATCH')
            <button type="button" onclick="confirmAction('show-activate-form', 'Activate {{ $customer->name }}?', 'Their account will become fully active.', 'Yes, activate', '#10b981')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activate
            </button>
        </form>
        <form method="POST" action="{{ route('admin.customers.block', $customer) }}" class="inline" id="show-block-pending-form">
            @csrf @method('PATCH')
            <button type="button" onclick="confirmAction('show-block-pending-form', 'Block {{ $customer->name }}?', 'They will be unable to login or place orders.', 'Yes, block', '#ef4444')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Block
            </button>
        </form>
    @endif

    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline" id="show-delete-form">
        @csrf @method('DELETE')
        <button type="button" onclick="confirmAction('show-delete-form', 'Delete {{ $customer->name }}?', 'This will soft-delete the account. It can be restored later.', 'Yes, delete', '#ef4444')"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ==================== PROFILE CARD ==================== --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="glass-card p-6">

            {{-- Avatar --}}
            <div class="text-center">
                @if($customer->photo)
                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="{{ $customer->name }}"
                         class="w-20 h-20 rounded-xl object-cover mx-auto mb-4 shadow-lg shadow-emerald-500/10">
                @else
                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4 shadow-lg shadow-emerald-500/10">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                @endif
                <h2 class="text-lg font-bold text-white">{{ $customer->name }}</h2>
                <p class="text-sm text-white/50 mt-0.5">
                    {{ $customer->username ? '@' . $customer->username : '' }}
                </p>
                <p class="text-xs text-white/30 mt-0.5">Customer #{{ $customer->id }}</p>
            </div>

            <hr class="border-white/5 my-5">

            {{-- Personal Details --}}
            <div class="space-y-3.5">
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-white/30 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div class="min-w-0">
                        <p class="text-xs text-white/30">Email</p>
                        <p class="text-sm text-white/70 truncate" title="{{ $customer->email }}">{{ $customer->email }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-white/30 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-white/30">Phone</p>
                        <p class="text-sm text-white/70">{{ $customer->phone ?? '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-white/30 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-white/30">Gender</p>
                        <p class="text-sm text-white/70">{{ $customer->gender ? ucfirst($customer->gender) : '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-white/30 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-white/30">Date of Birth</p>
                        <p class="text-sm text-white/70">{{ $customer->date_of_birth ? $customer->date_of_birth->format('M d, Y') : '—' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-white/5 my-5">

            {{-- Address --}}
            <div class="space-y-3.5">
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-white/30 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-white/30">Address</p>
                        <p class="text-sm text-white/70">{{ $customer->address ?? '—' }}</p>
                        @if($customer->city || $customer->country)
                            <p class="text-sm text-white/50">
                                {{ $customer->city }}{{ $customer->city && $customer->country ? ', ' : '' }}{{ $customer->country }}
                            </p>
                        @endif
                        <p class="text-xs text-white/30">{{ $customer->postal_code ?? '' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-white/5 my-5">

            {{-- Account Meta --}}
            <div class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-white/50">Registered</span>
                    <span class="text-sm text-white/70">{{ $customer->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-white/50">Last Login</span>
                    <span class="text-sm text-white/70">{{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-white/50">Email Verified</span>
                    @if($customer->email_verified_at)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Unverified
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-white/50">Account Status</span>
                    @php
                        $statusStyles = [
                            'active' => ['class' => 'bg-emerald-500/10 text-emerald-400', 'dot' => 'bg-emerald-400'],
                            'pending' => ['class' => 'bg-amber-500/10 text-amber-400', 'dot' => 'bg-amber-400'],
                            'blocked' => ['class' => 'bg-red-500/10 text-red-400', 'dot' => 'bg-red-400'],
                        ];
                        $s = $statusStyles[$customer->status] ?? ['class' => 'bg-gray-500/10 text-gray-400', 'dot' => 'bg-gray-400'];
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                        {{ ucfirst($customer->status) }}
                    </span>
                </div>
            </div>

        </div>
    </div>

    {{-- ==================== STATS + ORDERS + CHARTS ==================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Statistics Cards (7) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <x-admin::admin-card title="Lifetime Spending" :value="formatPrice($totalSpent)"
                icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                color="teal" />

            <x-admin::admin-card title="Avg. Order Value" :value="formatPrice($averageOrderValue)"
                icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                color="blue" />

            <x-admin::admin-card title="Completed" :value="$customer->completed_orders"
                icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                color="emerald" />

            <x-admin::admin-card title="Pending" :value="$customer->pending_orders"
                icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                color="amber" />

            <x-admin::admin-card title="Cancelled" :value="$customer->cancelled_orders"
                icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                color="red" />

            <x-admin::admin-card title="Returned" :value="$customer->returned_orders"
                icon="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"
                color="purple" />

            <x-admin::admin-card title="Wishlist" :value="$customer->wishlist_count"
                icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                color="pink" />
        </div>

        {{-- Monthly Spending Chart --}}
        @if($monthlySpending->isNotEmpty())
        <div class="glass-card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white/80">Monthly Spending (12 Months)</h3>
            </div>
            <div style="height: 200px;">
                <canvas id="spendingChart"></canvas>
            </div>
        </div>
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart !== 'undefined') {
                new Chart(document.getElementById('spendingChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($monthlySpending->keys()),
                        datasets: [{
                            label: 'Spending',
                            data: @json($monthlySpending->values()),
                            backgroundColor: 'rgba(16,185,129,0.25)',
                            borderColor: '#10b981',
                            borderWidth: 1.5,
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 9 } },
                                grid: { color: 'rgba(255,255,255,0.04)' },
                            },
                            y: {
                                ticks: {
                                    color: 'rgba(255,255,255,0.4)',
                                    font: { size: 9 },
                                    callback: v => '{{ getCurrencySymbol() }}' + v,
                                },
                                grid: { color: 'rgba(255,255,255,0.04)' },
                                beginAtZero: true,
                            }
                        }
                    }
                });
            }
        });
        </script>
        @endpush
        @endif

        {{-- Order History --}}
        <div class="glass-card overflow-hidden">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <h3 class="text-sm font-semibold text-white/80">Order History ({{ $orders->count() }})</h3>
                <a href="{{ route('admin.orders.index', ['search' => $customer->email]) }}"
                   class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition">
                    View All in Orders
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th class="text-center">Items</th>
                            <th>Payment</th>
                            <th>Pay Status</th>
                            <th>Order Status</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($orders as $order)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-sm font-medium text-emerald-400 hover:text-emerald-300 transition">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="text-sm text-white/50 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-center text-sm text-white/70">{{ $order->items_count }}</td>
                                <td class="text-sm text-white/70 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                <td>
                                    <x-admin::status-badge :status="$order->payment_status" type="payment" />
                                </td>
                                <td>
                                    <x-admin::status-badge :status="$order->status" type="order" />
                                </td>
                                <td class="text-right text-sm text-white/80 font-medium whitespace-nowrap">{{ formatPrice($order->grand_total) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.orders.invoice', $order) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition"
                                       target="_blank">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Invoice
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-white/30">
                                    <svg class="w-8 h-8 mx-auto text-white/10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p>No orders yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Wishlist --}}
        <div class="glass-card overflow-hidden">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <h3 class="text-sm font-semibold text-white/80">Recent Wishlist Items</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Added</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentWishlists as $wishlist)
                            @if($wishlist->product)
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                                @if($wishlist->product->image)
                                                    <img src="{{ asset('storage/' . $wishlist->product->image) }}"
                                                         alt="{{ $wishlist->product->name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-4 h-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-white/80 truncate max-w-[200px]">{{ $wishlist->product->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm text-white/70 whitespace-nowrap">{{ formatPrice($wishlist->product->final_price) }}</td>
                                    <td class="text-sm text-white/50 whitespace-nowrap">{{ $wishlist->created_at->diffForHumans() }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-white/30">
                                    <svg class="w-8 h-8 mx-auto text-white/10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <p>No wishlist items yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection
