@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Contact Messages']]" />
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-xl font-bold text-white">Contact Messages</h2><p class="text-sm text-white/40 mt-0.5">Messages sent through the contact form</p></div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="glass-card p-5">
        <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
        <p class="text-sm text-white/40 mt-1">Total Messages</p>
    </div>
    <div class="glass-card p-5">
        <p class="text-3xl font-bold {{ $stats['unread'] ? 'text-amber-400' : 'text-emerald-400' }}">{{ $stats['unread'] }}</p>
        <p class="text-sm text-white/40 mt-1">Unread</p>
    </div>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email or subject…" class="input-glass sm:max-w-xs">
    <select name="status" class="input-glass sm:w-44">
        <option value="">All messages</option>
        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
    @if (request()->has('q') || request()->has('status'))
        <a href="{{ route('admin.contact-messages.index') }}" class="btn-secondary">Reset</a>
    @endif
</form>

<x-admin::admin-table :headers="['From', 'Subject', 'Received', 'Status']">
    @forelse($messages as $message)
        <tr class="{{ $message->is_read ? '' : 'bg-emerald-500/[0.04]' }}">
            <td>
                <span class="font-semibold text-white/90 block">{{ $message->name }}</span>
                <span class="text-xs text-white/35">{{ $message->email }}</span>
            </td>
            <td class="max-w-md text-white/70 line-clamp-1">{{ $message->subject ?: '—' }}</td>
            <td class="text-white/40 text-sm">{{ $message->created_at->format('M d, Y h:i A') }}</td>
            <td>
                @if ($message->is_read)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Read</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400">Unread</span>
                @endif
            </td>
            <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.contact-messages.show', $message) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">View</a>
                    <button onclick="openModal('deleteMsg-{{ $message->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                    <x-admin::modal :id="'deleteMsg-' . $message->id" title="Delete Message?" action="{{ route('admin.contact-messages.destroy', $message) }}">Delete the message from <strong>{{ $message->name }}</strong>?</x-admin::modal>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="px-4 py-10 text-center text-white/30">No contact messages found.</td></tr>
    @endforelse
</x-admin::admin-table>
<x-admin::pagination :paginator="$messages" />
@endsection
