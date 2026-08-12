@extends('layouts.admin')
@section('title', 'Edit Category')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to Categories
    </a>
</div>

<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Edit Category</h1>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 space-y-5">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('name') border-red-500/50 @enderror">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Slug <span class="text-red-400">*</span></label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('slug') border-red-500/50 @enderror">
                @error('slug') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1.5">Description</label>
            <textarea name="description" rows="3" class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">{{ old('description', $category->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1.5">Image</label>
            @if($category->image)
                <div class="mb-2"><img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 rounded-lg object-cover border border-gray-700/50" onerror="this.onerror=null;this.insertAdjacentHTML('afterend','<div class=&quot;w-16 h-16 rounded-lg bg-gray-800 flex items-center justify-center text-xl&quot;>📁</div>');this.remove();"></div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20 transition">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }} class="rounded bg-gray-800 border-gray-600 text-emerald-500 focus:ring-emerald-500/50">
            <label class="text-sm font-medium text-gray-400">Active</label>
        </div>
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
