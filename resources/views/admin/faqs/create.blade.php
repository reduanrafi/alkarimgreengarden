@extends('layouts.admin')
@section('title', 'Create FAQ')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'FAQs', 'url' => route('admin.faqs.index')], ['label' => 'Create']]" />
<div class="max-w-2xl">
    <h2 class="text-xl font-bold text-white mb-6">Create FAQ</h2>
    <form action="{{ route('admin.faqs.store') }}" method="POST" class="glass-card p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category') }}" class="input-glass" placeholder="e.g. Shipping, Plant Care">
                @error('category') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Display Order</label>
                <input type="number" min="0" name="display_order" value="{{ old('display_order', 0) }}" class="input-glass">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Question <span class="text-red-400">*</span></label>
            <input type="text" name="question" value="{{ old('question') }}" class="input-glass @error('question') border-red-500/50 @enderror" placeholder="Do you offer free shipping?">
            @error('question') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-white/60 mb-1">Answer <span class="text-red-400">*</span></label>
            <textarea name="answer" rows="6" class="input-glass @error('answer') border-red-500/50 @enderror">{{ old('answer') }}</textarea>
            <p class="text-xs text-white/35 mt-1">Plain text. Line breaks are preserved.</p>
            @error('answer') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div><label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="rounded border-white/20 bg-white/5 text-emerald-500 focus:ring-emerald-500/50"><span class="text-sm font-medium text-white/60">Published</span></label></div>
        <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">Create FAQ</button><a href="{{ route('admin.faqs.index') }}" class="btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
