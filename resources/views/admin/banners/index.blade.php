@extends('layouts.admin')
@section('title', 'Banner Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Banner Management</h1>
        <p class="text-sm text-gray-400 mt-1">Manage homepage banners</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Add Banner
    </a>
</div>

<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Preview</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($banners as $banner)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="px-4 py-3 text-gray-500 font-medium">#{{ $banner->id }}</td>
                        <td class="px-4 py-3">
                            <div class="relative w-20 h-12 rounded-lg overflow-hidden border border-gray-700/50">
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-300">{{ $banner->title }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($banner->type === 'hero_banner')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-400">Hero</span>
                            @elseif($banner->type === 'homepage_carousel')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-sky-500/10 text-sky-400">Carousel</span>
                            @elseif($banner->type === 'homepage_fixed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400">Fixed</span>
                            @elseif($banner->type === 'especially_for_you')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-pink-500/10 text-pink-400">For You</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $banner->display_order }}</td>
                        <td class="px-4 py-3">
                            @if($banner->status)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-sm">{{ $banner->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ $banner->status ? 'bg-amber-500/10 text-amber-400 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' }} transition">
                                        {{ $banner->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Edit</a>
                                <button type="button" onclick="openModal('deleteBanner-{{ $banner->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-10 text-center text-gray-500">No banners found. Create your first banner!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Delete Modals --}}
@foreach($banners as $banner)
    <div id="deleteBanner-{{ $banner->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" onclick="if(event.target===this)closeModal('deleteBanner-{{ $banner->id }}')">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-gray-900 border border-gray-800 rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-white mb-2">Delete Banner?</h3>
            <p class="text-sm text-gray-400 mb-6">Are you sure you want to delete <strong class="text-gray-300">{{ $banner->title }}</strong>? This cannot be undone.</p>
            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST">
                @csrf @method('DELETE')
                <div class="flex items-center gap-3 justify-end">
                    <button type="button" onclick="closeModal('deleteBanner-{{ $banner->id }}')" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endsection
