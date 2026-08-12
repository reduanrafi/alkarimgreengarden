@extends('layouts.admin')
@section('title', 'Create Guide')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Plant Care Guides', 'url' => route('admin.plant-care.index')], ['label' => 'Create']]" />
<div class="max-w-3xl">
    <h2 class="text-xl font-bold text-white mb-6">Create Guide</h2>
    <form action="{{ route('admin.plant-care.store') }}" method="POST" enctype="multipart/form-data" class="glass-card p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="input-glass @error('title') border-red-500/50 @enderror" placeholder="How to Water Indoor Plants">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category') }}" class="input-glass" placeholder="e.g. Watering, Indoor, Succulents">
                @error('category') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Excerpt</label>
            <textarea name="excerpt" rows="2" class="input-glass" placeholder="A short summary shown on the guide card">{{ old('excerpt') }}</textarea>
            @error('excerpt') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Content <span class="text-red-400">*</span></label>
            <textarea name="content" rows="12" class="input-glass font-mono text-xs @error('content') border-red-500/50 @enderror" placeholder="Write the guide here. Basic HTML is supported: &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;...">{{ old('content') }}</textarea>
            @error('content') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Cover Image</label>
            <div class="border-2 border-dashed border-gray-700/50 rounded-lg p-6 text-center hover:border-emerald-500/30 transition cursor-pointer" onclick="document.getElementById('coverInput').click()">
                <svg class="w-8 h-8 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="input-glass">
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="input-glass" placeholder="plant care, watering, indoor plants">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="2" class="input-glass">{{ old('meta_description') }}</textarea>
        </div>
        <div><label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="rounded border-white/20 bg-white/5 text-emerald-500 focus:ring-emerald-500/50"><span class="text-sm font-medium text-white/60">Published</span></label></div>
        <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">Create Guide</button><a href="{{ route('admin.plant-care.index') }}" class="btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
