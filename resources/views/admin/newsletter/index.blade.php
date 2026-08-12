@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Newsletter Subscribers']]" />
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-xl font-bold text-white">Newsletter Subscribers</h2><p class="text-sm text-white/40 mt-0.5">Manage your email subscribers</p></div>
    <a href="{{ route('admin.newsletter.export') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>Export CSV</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="glass-card p-5">
        <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
        <p class="text-sm text-white/40 mt-1">Total Subscribers</p>
    </div>
    <div class="glass-card p-5">
        <p class="text-3xl font-bold text-emerald-400">{{ $stats['active'] }}</p>
        <p class="text-sm text-white/40 mt-1">Active</p>
    </div>
    <div class="glass-card p-5">
        <p class="text-3xl font-bold text-gray-400">{{ $stats['inactive'] }}</p>
        <p class="text-sm text-white/40 mt-1">Unsubscribed</p>
    </div>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by email…" class="input-glass sm:max-w-xs">
    <select name="status" class="input-glass sm:w-44">
        <option value="">All statuses</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Unsubscribed</option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
    @if (request()->has('q') || request()->has('status'))
        <a href="{{ route('admin.newsletter.index') }}" class="btn-secondary">Reset</a>
    @endif
</form>

<x-admin::admin-table :headers="['Email', 'Status', 'Subscribed At', 'Joined']">
    @forelse($subscribers as $subscriber)
        <tr>
            <td class="font-semibold text-white/90">{{ $subscriber->email }}</td>
            <td><x-admin::status-badge :status="$subscriber->is_active" /></td>
            <td class="text-white/50 text-sm">{{ $subscriber->subscribed_at?->format('M d, Y h:i A') ?? '—' }}</td>
            <td class="text-white/40 text-sm">{{ $subscriber->created_at?->format('M d, Y') ?? '—' }}</td>
            <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                    <form action="{{ route('admin.newsletter.toggle', $subscriber) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ $subscriber->is_active ? 'bg-amber-500/10 text-amber-400 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' }} transition">
                            {{ $subscriber->is_active ? 'Unsubscribe' : 'Activate' }}
                        </button>
                    </form>
                    <button onclick="openModal('deleteSub-{{ $subscriber->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                    <x-admin::modal :id="'deleteSub-' . $subscriber->id" title="Delete Subscriber?" action="{{ route('admin.newsletter.destroy', $subscriber) }}">Delete <strong>{{ $subscriber->email }}</strong> permanently?</x-admin::modal>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="px-4 py-10 text-center text-white/30">No subscribers found.</td></tr>
    @endforelse
</x-admin::admin-table>
<x-admin::pagination :paginator="$subscribers" />
@endsection
