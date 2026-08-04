@extends('layouts.admin')
@section('title', 'Edit Guide')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Plant Care Guides', 'url' => route('admin.plant-care.index')], ['label' => 'Edit']]" />
<div class="max-w-3xl">
    <h2 class="text-xl font-bold text-white mb-6">Edit Guide</h2>
    <form action="{{ route('admin.plant-care.update', $guide) }}" method="POST" enctype="multipart/form-data" class="glass-card p-6 space-y-5">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $guide->title) }}" class="input-glass @error('title') border-red-500/50 @enderror">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category', $guide->category) }}" class="input-glass">
                @error('category') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $guide->slug) }}" class="input-glass">
            @error('slug') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Excerpt</label>
            <textarea name="excerpt" rows="2" class="input-glass">{{ old('excerpt', $guide->excerpt) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Content <span class="text-red-400">*</span></label>
            <textarea name="content" rows="12" class="input-glass font-mono text-xs @error('content') border-red-500/50 @enderror">{{ old('content', $guide->content) }}</textarea>
            @error('content') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Cover Image</label>
            @if ($guide->cover_image)
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ asset('storage/' . $guide->cover_image) }}" alt="" class="w-20 h-14 rounded-lg object-cover border border-gray-700/50">
                    <span class="text-xs text-white/35">Current cover image. Upload a new one to replace it.</span>
                </div>
            @endif
            <div class="border-2 border-dashed border-gray-700/50 rounded-lg p-6 text-center hover:border-emerald-500/30 transition cursor-pointer" onclick="document.getElementById('coverInput').click()">
                <p class="text-sm text-gray-400 mb-1">Click to upload cover image</p>
                <p class="text-xs text-gray-600">JPG, JPEG, PNG, or WebP. Max 4 MB.</p>
                <p id="fileName" class="text-xs text-emerald-400 mt-2 hidden"></p>
            </div>
            <input id="coverInput" type="file" name="cover_image" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden" onchange="document.getElementById('fileName').textContent = this.files[0]?.name; document.getElementById('fileName').classList.remove('hidden')">
            @error('cover_image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $guide->meta_title) }}" class="input-glass">
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $guide->meta_keywords) }}" class="input-glass">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="2" class="input-glass">{{ old('meta_description', $guide->meta_description) }}</textarea>
        </div>
        <div><label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="status" value="1" {{ old('status', $guide->status) ? 'checked' : '' }} class="rounded border-white/20 bg-white/5 text-emerald-500 focus:ring-emerald-500/50"><span class="text-sm font-medium text-white/60">Published</span></label></div>
        <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">Update Guide</button><a href="{{ route('admin.plant-care.index') }}" class="btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
