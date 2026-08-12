@extends('layouts.admin')
@section('title', $product->name)
@push('styles')
<style>
.img-thumb { transition: opacity 0.2s ease, border-color 0.2s ease; cursor: pointer; }
.img-thumb:hover { border-color: rgba(16,185,129,0.5); }
.img-thumb.active { border-color: #10b981; opacity: 1; }
</style>
@endpush
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">{{ $product->name }}</h1>
        <p class="text-sm text-gray-400 mt-1">Product details and information</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.products.edit', $product) }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <button type="button" onclick="openDeleteModal()"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
        </button>
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>
</div>

{{-- Product Overview --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-6">
    {{-- Image Gallery --}}
    <div class="lg:col-span-2 bg-gray-900/50 border border-gray-800 rounded-xl p-5">
        <div class="flex flex-col items-center">
            <div class="w-full aspect-square max-w-sm rounded-xl overflow-hidden bg-gray-800/50 border border-gray-700/50 mb-3 flex items-center justify-center">
                @if($product->image)
                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" data-fallback="🌿">
                @else
                    <div id="mainImagePlaceholder" class="text-gray-600 text-center p-8">
                        <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm">No Image</p>
                    </div>
                @endif
            </div>
            @if($product->gallery_images && count($product->gallery_images) > 0)
                <div class="flex gap-2 flex-wrap justify-center">
                    @if($product->image)
                        <div class="w-14 h-14 rounded-lg overflow-hidden border-2 border-emerald-500 img-thumb active" onclick="changeMainImage(this, '{{ asset('storage/' . $product->image) }}')">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    @foreach($product->gallery_images as $img)
                        <div class="w-14 h-14 rounded-lg overflow-hidden border-2 border-transparent img-thumb" onclick="changeMainImage(this, '{{ asset('storage/' . $img) }}')">
                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @if($product->video_url)
            <div class="mt-4 pt-4 border-t border-gray-800/50">
                <p class="text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Video URL</p>
                <a href="{{ $product->video_url }}" target="_blank" rel="noopener noreferrer"
                   class="text-sm text-emerald-400 hover:text-emerald-300 transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Watch Video
                </a>
            </div>
        @endif
    </div>

    {{-- Product Info --}}
    <div class="lg:col-span-3 space-y-4">
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $product->slug }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($product->status)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Inactive</span>
                    @endif
                    @if($product->featured)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Featured
                        </span>
                    @endif
                    @if($product->pre_order)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400">Pre-Order</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-800/50">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->sku ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->category?->name ?? 'Uncategorized' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->brand ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-300 mb-4">Pricing</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Buying Price</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->buying_price ? formatPrice($product->buying_price) : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Regular Price</p>
                    <p class="text-sm font-semibold text-gray-200 mt-1">{{ formatPrice($product->price) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</p>
                    <p class="text-sm text-gray-300 mt-1">
                        @if($product->discount_type && $product->discount_price)
                            @if($product->discount_type === 'percentage')
                                {{ $product->discount_price }}%
                            @else
                                {{ formatPrice($product->discount_price) }}
                            @endif
                            <span class="text-xs text-gray-600">({{ ucfirst($product->discount_type) }})</span>
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Final Price</p>
                    <p class="text-sm font-bold text-emerald-400 mt-1">{{ formatPrice($product->final_price) }}</p>
                </div>
            </div>
        </div>

        {{-- Inventory --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-300 mb-4">Inventory</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-sm font-semibold {{ $product->stock <= 0 ? 'text-red-400' : ($product->is_low_stock ? 'text-amber-400' : 'text-gray-200') }}">{{ $product->stock }}</span>
                        @if($product->is_low_stock)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400">Low Stock</span>
                        @elseif($product->stock <= 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400">Out of Stock</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Barcode</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->barcode ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</p>
                    <p class="text-sm text-gray-300 mt-1">{{ $product->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Descriptions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-300 mb-3">Short Description</h3>
        <div class="text-sm text-gray-400 leading-relaxed">
            {{ $product->description ?? 'No description provided.' }}
        </div>
    </div>
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-300 mb-3">Full Description</h3>
        <div class="text-sm text-gray-400 leading-relaxed prose prose-invert max-w-none">
            {!! $product->full_description ?: 'No full description provided.' !!}
        </div>
    </div>
</div>

{{-- Specifications + Media --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- Specifications --}}
    @php
        $specs = $product->productAttributeValues
            ->groupBy(fn($pav) => $pav->attribute->name)
            ->map(fn($group) => $group->pluck('attributeValue.value')->unique()->implode(', '));
    @endphp
    @if($specs->isNotEmpty())
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-300 mb-4">Specifications</h3>
        <div class="space-y-3">
            @foreach($specs as $name => $values)
            <div class="flex items-baseline gap-3 pb-3 border-b border-gray-800/30 last:border-0 last:pb-0">
                <span class="text-sm font-medium text-gray-400 min-w-[100px]">{{ $name }}</span>
                <span class="text-sm text-gray-200">{{ $values }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Media / Gallery --}}
    @if($product->gallery_images && count($product->gallery_images) > 0)
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-300 mb-4">Gallery</h3>
        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
            @foreach($product->gallery_images as $img)
            <div class="aspect-square rounded-lg overflow-hidden bg-gray-800/50 border border-gray-700/50">
                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" onclick="if(event.target===this)closeDeleteModal()">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative bg-gray-900 border border-gray-800 rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Delete Product?</h3>
                <p class="text-sm text-gray-400 mt-0.5">This action cannot be undone.</p>
            </div>
        </div>
        <p class="text-sm text-gray-400 mb-6">Are you sure you want to delete <strong class="text-gray-200">{{ $product->name }}</strong>?</p>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
            @csrf @method('DELETE')
            <div class="flex items-center gap-3 justify-end">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(el, src) {
    document.querySelectorAll('.img-thumb').forEach(t => {
        t.classList.remove('active');
        t.style.borderColor = 'transparent';
    });
    el.classList.add('active');
    el.style.borderColor = '#10b981';
    const mainImg = document.getElementById('mainImage');
    if (mainImg) {
        mainImg.src = src;
    }
}

function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endpush