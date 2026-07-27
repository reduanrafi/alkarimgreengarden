@extends('layouts.admin')
@section('title', 'Notifications')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Notifications</h1>
        <p class="text-sm text-gray-400 mt-1">
            @if($unreadCount > 0)
                {{ $unreadCount }} unread notification(s)
            @else
                All caught up!
            @endif
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        @if($unreadCount > 0)
            <a href="{{ route('admin.notifications.mark-all-read') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mark All Read
            </a>
        @endif
        <a href="{{ route('admin.notifications.clear-all') }}"
           onclick="return confirm('Clear all notifications?')"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Clear All
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="mb-6">
    <div class="flex flex-wrap gap-3">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, module..." class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg pl-9 pr-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
        </div>
        <select name="status" class="bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition min-w-[130px]">
            <option value="">All Status</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
        </select>
        <select name="date_filter" class="bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition min-w-[140px]">
            <option value="">All Time</option>
            <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today</option>
            <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>This Week</option>
            <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>This Month</option>
        </select>
        <button type="submit" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Filter</button>
        @if(request('search') || request('status') || request('date_filter'))
            <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Clear</a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 bg-gray-900/30">
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Description</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Module</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Created</th>
                    <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/30">
                @forelse($notifications as $n)
                <tr class="hover:bg-white/[0.03] transition-colors {{ !$n->is_read ? 'bg-emerald-500/[0.02]' : '' }}">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if(!$n->is_read)
                                <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                            @endif
                            <span class="font-medium {{ !$n->is_read ? 'text-gray-200' : 'text-gray-400' }}">{{ $n->title }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate hidden sm:table-cell">{{ $n->description ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-300">{{ $n->module }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $n->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($n->is_read)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Read</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">New</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            @if(!$n->is_read)
                                <a href="{{ route('admin.notifications.read', $n) }}" class="p-2 rounded-lg text-gray-500 hover:text-emerald-400 hover:bg-emerald-500/10 transition" title="Mark as Read">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </a>
                            @endif
                            <a href="{{ route('admin.notifications.destroy', $n) }}"
                               onclick="return confirm('Delete this notification?')"
                               class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500">No notifications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($notifications->hasPages())
    <div class="mt-5">{{ $notifications->links() }}</div>
@endif
@endsection
