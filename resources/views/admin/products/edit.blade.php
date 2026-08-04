@extends('layouts.admin')
@section('title', 'Edit Product')
@push('styles')
<style>
    .img-preview { transition: opacity 0.3s ease; }
</style>
@endpush
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to Products
    </a>
</div>

<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Product</h1>
            <p class="text-sm text-gray-400 mt-1">Update product details</p>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Section 1: Basic Information --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Basic Information</h2>
                    <p class="text-xs text-gray-500">Product name, category, and descriptions</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Product Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="productName" value="{{ old('name', $product->name) }}"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('name') border-red-500/50 @enderror"
                           oninput="generateSlug(this.value)">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Slug <span class="text-red-400">*</span></label>
                    <input type="text" name="slug" id="productSlug" value="{{ old('slug', $product->slug) }}"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('slug') border-red-500/50 @enderror font-mono">
                    @error('slug') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Category <span class="text-red-400">*</span></label>
                    <select name="category_id" class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('category_id') border-red-500/50 @enderror">
                        <option value="" class="bg-gray-900">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }} class="bg-gray-900">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" placeholder="e.g. Nike, Zara, H&M"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Short Description <span class="text-red-400">*</span></label>
                <textarea name="description" rows="3"
                          class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('description') border-red-500/50 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Full Description</label>
                <textarea name="full_description" rows="6"
                          class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">{{ old('full_description', $product->full_description) }}</textarea>
                <p class="text-xs text-gray-600 mt-1">Supports HTML content for rich product descriptions.</p>
            </div>
        </div>

        {{-- Section 2: Product Attributes --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Product Attributes</h2>
                    <p class="text-xs text-gray-500">Select attribute values for this product</p>
                </div>
            </div>
            <div id="attributesContainer">
                @php
                    $activeAttributes = \App\Models\Attribute::with('values')->where('status', true)->get();
                    $selectedValues = old('attribute_values', $product->productAttributeValues->pluck('attribute_value_id')->toArray());
                @endphp
                @if($activeAttributes->count() > 0)
                    @foreach($activeAttributes as $attr)
                        @if($attr->values->count() > 0)
                            <div class="mb-4 pb-4 border-b border-gray-800/30 last:border-0 last:mb-0 last:pb-0">
                                <p class="text-sm font-medium text-gray-300 mb-2.5">{{ $attr->name }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($attr->values as $val)
                                        @php $isSel = in_array($val->id, $selectedValues); @endphp
                                        <button type="button"
                                                onclick="toggleAttr(this, {{ $val->id }})"
                                                class="attr-value px-3 py-1.5 rounded-lg text-sm font-medium border transition-all duration-200
                                                       {{ $isSel ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/50' : 'bg-gray-800/40 text-gray-400 border-gray-700/50 hover:border-emerald-500/30' }}">
                                            {{ $val->value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">No attributes available. <a href="{{ route('admin.attributes.index') }}" class="text-emerald-400 hover:text-emerald-300">Create attributes</a> first.</p>
                @endif
            </div>
            <div id="selectedAttributeValues"></div>
        </div>

        {{-- Section 3: Pricing --}}
        @php $currencySymbol = getCurrencySymbol(); @endphp
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Pricing</h2>
                    <p class="text-xs text-gray-500">Cost, selling price, and discounting</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Buying Price (Cost)</label>
                    <div class="flex rounded-lg border border-gray-700/50 focus-within:border-emerald-500/50 focus-within:ring-1 focus-within:ring-emerald-500/20 transition @error('buying_price') border-red-500/50 @enderror">
                        <span class="inline-flex items-center px-3 bg-gray-800/50 text-gray-500 text-sm border-r border-gray-700/50 min-w-10 justify-center">{{ $currencySymbol }}</span>
                        <input type="number" step="0.01" min="0" name="buying_price" value="{{ old('buying_price', $product->buying_price) }}"
                               class="flex-1 min-w-0 bg-gray-800/50 px-3 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Regular Price <span class="text-red-400">*</span></label>
                    <div class="flex rounded-lg border border-gray-700/50 focus-within:border-emerald-500/50 focus-within:ring-1 focus-within:ring-emerald-500/20 transition @error('price') border-red-500/50 @enderror">
                        <span class="inline-flex items-center px-3 bg-gray-800/50 text-gray-500 text-sm border-r border-gray-700/50 min-w-10 justify-center">{{ $currencySymbol }}</span>
                        <input type="number" step="0.01" min="0" name="price" id="regularPrice" value="{{ old('price', $product->price) }}"
                               class="flex-1 min-w-0 bg-gray-800/50 px-3 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none"
                               oninput="calculateFinalPrice()">
                    </div>
                    @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Discount Type</label>
                    <select name="discount_type" id="discountType" onchange="calculateFinalPrice()"
                            class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                        <option value="" class="bg-gray-900">No Discount</option>
                        <option value="fixed" {{ old('discount_type', $product->discount_type) === 'fixed' ? 'selected' : '' }} class="bg-gray-900">Fixed ({{ $currencySymbol }})</option>
                        <option value="percentage" {{ old('discount_type', $product->discount_type) === 'percentage' ? 'selected' : '' }} class="bg-gray-900">Percentage (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Discount Value</label>
                    <input type="number" step="0.01" min="0" name="discount_price" id="discountPrice" value="{{ old('discount_price', $product->discount_price) }}"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('discount_price') border-red-500/50 @enderror"
                           oninput="calculateFinalPrice()">
                    @error('discount_price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Final Selling Price</label>
                    <div class="flex rounded-lg border border-gray-700/30 focus-within:border-emerald-500/50 focus-within:ring-1 focus-within:ring-emerald-500/20 transition">
                        <span class="inline-flex items-center px-3 bg-gray-800/30 text-gray-500 text-sm border-r border-gray-700/30 min-w-10 justify-center">{{ $currencySymbol }}</span>
                        <input type="text" id="finalPrice" readonly
                               class="flex-1 min-w-0 bg-gray-800/30 px-3 py-2.5 text-sm text-emerald-400 font-semibold outline-none cursor-default">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Media --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Media</h2>
                    <p class="text-xs text-gray-500">Product images and video</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Thumbnail Image <span class="text-red-400">*</span></label>
                    @if($product->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 rounded-lg object-cover border border-gray-700/50" onerror="this.onerror=null;this.insertAdjacentHTML('afterend','<div class=&quot;w-20 h-20 rounded-lg bg-gray-800/50 flex items-center justify-center text-2xl&quot;>🌿</div>');this.remove();">
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-700/50 rounded-lg p-4 text-center hover:border-emerald-500/30 transition cursor-pointer" onclick="document.getElementById('thumbnailInput').click()">
                        <div id="thumbnailPreview" class="hidden mb-3">
                            <img id="thumbnailImg" class="w-24 h-24 rounded-lg object-cover mx-auto border border-gray-700/50">
                        </div>
                        <div id="thumbnailPlaceholder">
                            <svg class="w-8 h-8 text-gray-600 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-500">Click to replace thumbnail</p>
                            <p class="text-xs text-gray-600 mt-0.5">jpg, jpeg, png, webp • Max 2MB</p>
                        </div>
                    </div>
                    <input type="file" name="image" id="thumbnailInput" accept="image/*" class="hidden" onchange="previewThumbnail(this)">
                    @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Gallery Images</label>
                    @if($product->gallery_images)
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($product->gallery_images as $index => $galleryImage)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $galleryImage) }}" class="w-14 h-14 rounded-lg object-cover border border-gray-700/50">
                                    <a href="{{ route('admin.products.deleteGalleryImage', [$product, $index]) }}"
                                       class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition"
                                       onclick="return confirm('Remove this image?')">&times;</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-700/50 rounded-lg p-4 text-center hover:border-emerald-500/30 transition cursor-pointer" onclick="document.getElementById('galleryInput').click()">
                        <div id="galleryPreviews" class="flex flex-wrap gap-2 mb-3 hidden"></div>
                        <div id="galleryPlaceholder">
                            <svg class="w-8 h-8 text-gray-600 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-500">Click to add more images</p>
                            <p class="text-xs text-gray-600 mt-0.5">Max 5 images, 2MB each</p>
                        </div>
                    </div>
                    <input type="file" name="gallery_images[]" id="galleryInput" multiple accept="image/*" class="hidden" onchange="previewGallery(this)">
                    @error('gallery_images.*') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Product Video URL</label>
                <input type="url" name="video_url" value="{{ old('video_url', $product->video_url) }}" placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('video_url') border-red-500/50 @enderror">
                @error('video_url') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 5: Inventory --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Inventory</h2>
                    <p class="text-xs text-gray-500">Stock management and tracking</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Stock <span class="text-red-400">*</span></label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition @error('stock') border-red-500/50 @enderror">
                    @error('stock') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Alert Qty</label>
                    <input type="number" min="1" name="low_stock_alert_quantity" value="{{ old('low_stock_alert_quantity', $product->low_stock_alert_quantity) }}" placeholder="e.g. 5"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                    <p class="text-xs text-gray-600 mt-1">Low stock threshold</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="e.g. PROD-001"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Barcode</label>
                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" placeholder="UPC / EAN"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                </div>
            </div>
        </div>

        {{-- Section 6: Product Settings --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">Product Settings</h2>
                    <p class="text-xs text-gray-500">Visibility and availability options</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-6">
                <label class="relative inline-flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-amber-500 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:after:translate-x-full"></div>
                    <span class="text-sm font-medium text-gray-400 group-hover:text-gray-300 transition">Featured Product</span>
                </label>

                <label class="relative inline-flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-emerald-500 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:after:translate-x-full"></div>
                    <span class="text-sm font-medium text-gray-400 group-hover:text-gray-300 transition">Active</span>
                </label>

                <label class="relative inline-flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="pre_order" value="1" {{ old('pre_order', $product->pre_order) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-purple-500 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:after:translate-x-full"></div>
                    <span class="text-sm font-medium text-gray-400 group-hover:text-gray-300 transition">Pre Order</span>
                </label>
            </div>
        </div>

        {{-- Section 7: SEO --}}
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 mb-5">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-800/50">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white">SEO</h2>
                    <p class="text-xs text-gray-500">Search engine optimization metadata</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" placeholder="Product title for search engines"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Meta Description</label>
                    <textarea name="meta_description" rows="2" placeholder="Brief description for search results"
                              class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" placeholder="plants, planters, gardening"
                           class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
                    <p class="text-xs text-gray-600 mt-1">Comma-separated keywords</p>
                </div>
            </div>
        </div>

        {{-- Section 8: Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Cancel</a>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition ml-auto">Back</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const slugField = document.getElementById('productSlug');
    if (slugField) {
        slugField.addEventListener('input', function() { this.dataset.modified = 'true'; });
    }

    function generateSlug(value) {
        const slugField = document.getElementById('productSlug');
        if (!slugField || slugField.dataset.modified) return;
        slugField.value = value.toLowerCase()
            .replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/^-+|-+$/g, '');
    }

    function calculateFinalPrice() {
        const price = parseFloat(document.getElementById('regularPrice').value) || 0;
        const discountType = document.getElementById('discountType').value;
        const discountValue = parseFloat(document.getElementById('discountPrice').value) || 0;
        let final = price;
        if (discountType === 'fixed') final = price - discountValue;
        else if (discountType === 'percentage') final = price - (price * discountValue / 100);
        const num = Math.max(0, final);
        document.getElementById('finalPrice').value = num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function previewThumbnail(input) {
        const preview = document.getElementById('thumbnailPreview');
        const placeholder = document.getElementById('thumbnailPlaceholder');
        const img = document.getElementById('thumbnailImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) { img.src = e.target.result; preview.classList.remove('hidden'); placeholder.classList.add('hidden'); };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGallery(input) {
        const container = document.getElementById('galleryPreviews');
        const placeholder = document.getElementById('galleryPlaceholder');
        container.innerHTML = '';
        if (input.files && input.files.length > 0) {
            placeholder.classList.add('hidden');
            container.classList.remove('hidden');
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                const img = document.createElement('img');
                img.className = 'w-14 h-14 rounded-lg object-cover border border-gray-700/50';
                reader.onload = function(e) { img.src = e.target.result; };
                reader.readAsDataURL(file);
                wrapper.appendChild(img);
                container.appendChild(wrapper);
            });
        } else {
            placeholder.classList.remove('hidden');
            container.classList.add('hidden');
        }
    }

        calculateFinalPrice();

        let selectedAttrs = @json($selectedValues);
        syncAttrInputs();

        function toggleAttr(btn, valueId) {
            const idx = selectedAttrs.indexOf(valueId);
            if (idx > -1) {
                selectedAttrs.splice(idx, 1);
                btn.classList.remove('bg-emerald-500/20', 'text-emerald-400', 'border-emerald-500/50');
                btn.classList.add('bg-gray-800/40', 'text-gray-400', 'border-gray-700/50', 'hover:border-emerald-500/30');
            } else {
                selectedAttrs.push(valueId);
                btn.classList.remove('bg-gray-800/40', 'text-gray-400', 'border-gray-700/50', 'hover:border-emerald-500/30');
                btn.classList.add('bg-emerald-500/20', 'text-emerald-400', 'border-emerald-500/50');
            }
            syncAttrInputs();
        }

        function syncAttrInputs() {
            const container = document.getElementById('selectedAttributeValues');
            if (!container) return;
            container.innerHTML = '';
            selectedAttrs.forEach(function(id) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'attribute_values[]';
                input.value = id;
                container.appendChild(input);
            });
        }
    </script>
@endpush
