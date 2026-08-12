@extends('layouts.admin')
@section('title', 'Plant Care Guides')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Plant Care Guides']]" />
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-xl font-bold text-white">Plant Care Guides</h2><p class="text-sm text-white/40 mt-0.5">Create and manage plant care guides</p></div>
    <a href="{{ route('admin.plant-care.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>Add Guide</a>
</div>
<x-admin::admin-table :headers="['Title', 'Category', 'Updated', 'Status']">
    @forelse($guides as $guide)
        <tr>
            <td class="max-w-md">
                <div class="flex items-center gap-3">
                    @if ($guide->cover_image)
                        <img src="{{ asset('storage/' . $guide->cover_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center shrink-0">🌱</div>
                    @endif
                    <div>
                        <span class="font-semibold text-white/90 block">{{ $guide->title }}</span>
                        <span class="text-xs text-white/35">{{ $guide->slug }}</span>
                    </div>
                </div>
            </td>
            <td>
                @if ($guide->category)
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400">{{ $guide->category }}</span>
                @else
                    <span class="text-white/30">—</span>
                @endif
            </td>
            <td class="text-white/50 text-sm">{{ $guide->updated_at->format('M d, Y') }}</td>
            <td>
                <form action="{{ route('admin.plant-care.toggle', $guide) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2">
                        <span class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $guide->status ? 'bg-emerald-500/80' : 'bg-gray-600' }}">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition {{ $guide->status ? 'translate-x-5' : 'translate-x-1' }}"></span>
                        </span>
                        <span class="text-xs {{ $guide->status ? 'text-emerald-400' : 'text-gray-400' }}">{{ $guide->status ? 'Published' : 'Draft' }}</span>
                    </button>
                </form>
            </td>
            <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('care.show', $guide->slug) }}" target="_blank" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition">View</a>
                    <a href="{{ route('admin.plant-care.edit', $guide) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Edit</a>
                    <button onclick="openModal('deleteGuide-{{ $guide->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                    <x-admin::modal :id="'deleteGuide-' . $guide->id" title="Delete Guide?" action="{{ route('admin.plant-care.destroy', $guide) }}">Delete <strong>{{ $guide->title }}</strong>?</x-admin::modal>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="px-4 py-10 text-center text-white/30">No plant care guides found.</td></tr>
    @endforelse
</x-admin::admin-table>
<x-admin::pagination :paginator="$guides" />
@endsection
