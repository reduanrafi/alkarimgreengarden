@extends('layouts.app')

@section('title', 'Search - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 font-serif">Search Products</h1>
        <p class="text-gray-400 mt-2">Find your perfect style</p>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('search') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8" data-ajax>
        <div class="relative mb-6">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, description, fabric, color..."
                   class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none bg-gray-50 transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
                <select name="category" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Fabric</label>
                <select name="fabric" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    <option value="">All Fabrics</option>
                    @foreach($fabrics as $f)
                        <option value="{{ $f }}" {{ request('fabric') === $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Color</label>
                <select name="color" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    <option value="">All Colors</option>
                    @foreach($colors as $c)
                        <option value="{{ $c }}" {{ request('color') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Sort By</label>
                <select name="sort" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">Price:</span>
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-20 sm:w-24 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                <span class="text-gray-300">—</span>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-20 sm:w-24 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
            </div>
            <div class="flex items-center gap-2 ml-auto">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Search
                </button>
                @if(request()->anyFilled(['q','category','fabric','color','min_price','max_price','sort']))
                    <a href="{{ route('search') }}" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition rounded-xl hover:bg-gray-50 border border-gray-200">Clear</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Results Header --}}
    @if(request()->anyFilled(['q','category','fabric','color','min_price','max_price']))
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">
                <span class="font-medium text-gray-900">{{ $products->total() }}</span> result(s) found
                @if(request('q'))
                    for "<span class="text-indigo-600 font-medium">{{ request('q') }}</span>"
                @endif
            </p>
        </div>
    @endif

    {{-- Active Filters --}}
    @if(request()->anyFilled(['q','category','fabric','color','min_price','max_price']))
        <div class="flex flex-wrap items-center gap-2 mb-6">
            @if(request('q'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs rounded-full border border-indigo-100">
                    "{{ request('q') }}"
                    <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-indigo-900 ml-1">&times;</a>
                </span>
            @endif
            @if(request('category'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-sky-50 text-sky-700 text-xs rounded-full border border-sky-100">
                    {{ request('category') }}
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="hover:text-sky-900 ml-1">&times;</a>
                </span>
            @endif
            @if(request('fabric'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-700 text-xs rounded-full border border-amber-100">
                    {{ request('fabric') }}
                    <a href="{{ request()->fullUrlWithQuery(['fabric' => null]) }}" class="hover:text-amber-900 ml-1">&times;</a>
                </span>
            @endif
            @if(request('color'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-50 text-purple-700 text-xs rounded-full border border-purple-100">
                    {{ request('color') }}
                    <a href="{{ request()->fullUrlWithQuery(['color' => null]) }}" class="hover:text-purple-900 ml-1">&times;</a>
                </span>
            @endif
            @if(request('min_price') || request('max_price'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs rounded-full border border-emerald-100">
                    ${{ request('min_price') ?: '0' }} - ${{ request('max_price') ?: '∞' }}
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="hover:text-emerald-900 ml-1">&times;</a>
                </span>
            @endif
        </div>
    @endif

    {{-- Results --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @elseif(request()->anyFilled(['q','category','fabric','color','min_price','max_price']))
        {{-- Empty State --}}
        <x-empty-state
            icon="search"
            title="No Results Found"
            message="We couldn't find any products matching your search. Try different keywords or filters."
            :action="route('products.index')"
            actionText="Browse All Products"
        />
    @else
        {{-- Initial State --}}
        <x-empty-state
            icon="search"
            title="Search our collection"
            message="Enter a keyword above to find exactly what you're looking for."
        />
    @endif
</div>
<x-product-preview />
@endsection
