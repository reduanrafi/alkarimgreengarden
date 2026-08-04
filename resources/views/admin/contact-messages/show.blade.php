@extends('layouts.admin')
@section('title', 'Message')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Contact Messages', 'url' => route('admin.contact-messages.index')], ['label' => 'Message #' . $message->id]]" />
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-white">Contact Message</h2>
    <a href="{{ route('admin.contact-messages.index') }}" class="btn-secondary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Back</a>
</div>
<div class="max-w-3xl">
    <div class="glass-card p-6 space-y-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm text-white/40">From</p>
                <p class="text-lg font-bold text-white">{{ $message->name }}</p>
                <a href="mailto:{{ $message->email }}" class="text-sm text-emerald-400 hover:underline">{{ $message->email }}</a>
            </div>
            <span class="text-xs text-white/35">{{ $message->created_at->format('M d, Y h:i A') }}</span>
        </div>
        <div class="border-t border-gray-800 pt-4">
            <p class="text-sm text-white/40 mb-1">Subject</p>
            <p class="font-semibold text-white/90">{{ $message->subject ?: 'No subject' }}</p>
        </div>
        <div class="border-t border-gray-800 pt-4">
            <p class="text-sm text-white/40 mb-2">Message</p>
            <p class="text-white/80 leading-relaxed whitespace-pre-line">{{ $message->message }}</p>
        </div>
        <div class="flex items-center gap-3 border-t border-gray-800 pt-5">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject ?: 'Your message') }}" class="btn-primary">Reply by Email</a>
            <button onclick="openModal('deleteMsg-{{ $message->id }}')" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
            <x-admin::modal :id="'deleteMsg-' . $message->id" title="Delete Message?" action="{{ route('admin.contact-messages.destroy', $message) }}">Delete the message from <strong>{{ $message->name }}</strong>?</x-admin::modal>
        </div>
    </div>
</div>
@endsection
