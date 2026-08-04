@extends('layouts.admin')
@section('title', 'Products')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Products</h1>
        <p class="text-sm text-gray-400 mt-1">Manage your product inventory</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Add Product
    </a>
</div>

<form method="GET" class="mb-6" id="filterForm">
    <div class="flex flex-wrap gap-3">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, SKU, category..." class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg pl-9 pr-4 py-2.5 text-sm text-gray-300 placeholder-gray-500 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition">
        </div>
        <select name="category" class="bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition min-w-[140px]">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="stock" class="bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition min-w-[130px]">
            <option value="">All Stock</option>
            <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>🟢 In Stock</option>
            <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>🔴 Low Stock</option>
            <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>⚫ Out of Stock</option>
        </select>
        <select name="sort" class="bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition min-w-[150px]">
            <option value="">Latest</option>
            <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>Stock ↑ Ascending</option>
            <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Stock ↓ Descending</option>
            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price ↑ Ascending</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price ↓ Descending</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
        </select>
        <button type="submit" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Filter</button>
        @if(request('search') || request('category') || request('stock') || request('sort'))
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Clear</a>
        @endif
    </div>
</form>

<div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <form id="bulkForm" action="{{ route('admin.products.bulk-update-stock') }}" method="POST">
            @csrf
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-900/30">
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                            <input type="checkbox" id="selectAll" class="rounded bg-gray-800 border-gray-600 text-emerald-500 focus:ring-emerald-500/30">
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alert</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/30">
                    @forelse($products as $product)
                        <tr class="hover:bg-white/[0.03] transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded bg-gray-800 border-gray-600 text-emerald-500 focus:ring-emerald-500/30">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-9 h-9 rounded-lg object-cover" onerror="this.onerror=null;this.insertAdjacentHTML('afterend','<div class=&quot;w-9 h-9 rounded-lg bg-gray-800/50 flex items-center justify-center text-sm&quot;>👕</div>');this.remove();">
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-gray-800/50 flex items-center justify-center text-sm">👕</div>
                                    @endif
                                    <div>
                                        <span class="font-medium text-gray-200">{{ $product->name }}</span>
                                        <p class="text-xs text-gray-600">{{ $product->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-gray-600 text-xs">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-200">{{ formatPrice($product->price) }}</span>
                                @if($product->discount_price)
                                    <span class="text-xs text-emerald-400 block mt-0.5">{{ formatPrice($product->discount_price) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusIcon = match($product->stock_status) {
                                        'out_of_stock' => ['bg-red-500/10 text-red-400', 'bg-red-400', '⚫ Out of Stock'],
                                        'low_stock' => ['bg-amber-500/10 text-amber-400', 'bg-amber-400', '🔴 ' . $product->stock],
                                        default => ['bg-emerald-500/10 text-emerald-400', 'bg-emerald-400', '🟢 ' . $product->stock],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $statusIcon[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusIcon[1] }}"></span>
                                    {{ $statusIcon[2] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $product->low_stock_alert_quantity ?? 5 }}</td>
                            <td class="px-4 py-3">
                                @if($product->status)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($product->featured)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium bg-amber-500/10 text-amber-400 px-2.5 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Featured
                                    </span>
                                @else
                                    <span class="text-sm text-gray-700">★</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="p-2 rounded-lg text-gray-500 hover:text-emerald-400 hover:bg-emerald-500/10 transition"
                                       title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="p-2 rounded-lg text-gray-500 hover:text-emerald-400 hover:bg-emerald-500/10 transition"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button type="button" onclick="openModal('deleteProduct-{{ $product->id }}')"
                                            class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-10 text-center text-gray-500">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div id="bulkActions" class="hidden border-t border-gray-800/50 px-4 py-3 flex items-center gap-3 bg-gray-900/30">
                <span id="selectedCount" class="text-sm text-gray-400">0 selected</span>
                <span class="text-gray-600">|</span>
                <label class="text-sm text-gray-400">Set stock to:</label>
                <input type="number" name="stock_value" min="0" value="0" class="w-20 bg-gray-800/50 border border-gray-700/50 rounded-lg px-3 py-1.5 text-sm text-gray-300 outline-none focus:border-emerald-500/50">
                <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Update</button>
            </div>
        </form>
    </div>
</div>

@if($products->hasPages())
    <div class="mt-5">{{ $products->links() }}</div>
@endif

{{-- Delete Modals --}}
@foreach($products as $product)
    <div id="deleteProduct-{{ $product->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" onclick="if(event.target===this)closeModal('deleteProduct-{{ $product->id }}')">
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
                    <button type="button" onclick="closeModal('deleteProduct-{{ $product->id }}')" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// Bulk actions
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.product-checkbox');
const bulkActions = document.getElementById('bulkActions');
const selectedCount = document.getElementById('selectedCount');

function updateBulkUI() {
    const checked = document.querySelectorAll('.product-checkbox:checked').length;
    if (checked > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = checked + ' selected';
    } else {
        bulkActions.classList.add('hidden');
    }
}

selectAll.addEventListener('change', function() {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkUI();
});

checkboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkUI);
});
</script>
@endsection