@extends('layouts.admin')
@section('title', 'Categories')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Categories</h1>
        <p class="text-sm text-gray-400 mt-1">Manage product categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Add Category
    </a>
</div>

<form method="GET" class="mb-6">
    <div class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition w-full max-w-sm">
        <button type="submit" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Search</button>
        @if(request('search'))<a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Clear</a>@endif
    </div>
</form>

<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($categories as $category)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="px-4 py-3 text-gray-500 font-medium">#{{ $category->id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-9 h-9 rounded-lg object-cover" onerror="this.onerror=null;this.insertAdjacentHTML('afterend','<div class=&quot;w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center text-sm&quot;>📁</div>');this.remove();">
                                @else
                                    <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center text-sm">📁</div>
                                @endif
                                <span class="font-medium text-gray-300">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $category->slug }}</td>
                        <td class="px-4 py-3">
                            @if($category->status)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $category->products_count ?? $category->products()->count() }}</td>
                        <td class="px-4 py-3 text-gray-500 text-sm">{{ $category->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Edit</a>
                                <button type="button" onclick="openModal('deleteCat-{{ $category->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-gray-500">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($categories->hasPages())
    <div class="mt-4">{{ $categories->links() }}</div>
@endif

{{-- Delete Modals --}}
@foreach($categories as $category)
    <div id="deleteCat-{{ $category->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" onclick="if(event.target===this)closeModal('deleteCat-{{ $category->id }}')">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-gray-900 border border-gray-800 rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-white mb-2">Delete Category?</h3>
            <p class="text-sm text-gray-400 mb-6">Are you sure? Products in <strong class="text-gray-300">{{ $category->name }}</strong> will be uncategorized.</p>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                @csrf @method('DELETE')
                <div class="flex items-center gap-3 justify-end">
                    <button type="button" onclick="closeModal('deleteCat-{{ $category->id }}')" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Cancel</button>
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
