@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - ' . config('app.name') : 'Products - ' . config('app.name'))

@section('content')
<div
    x-data="{
        mobileFiltersOpen: false,
        searchQuery: '{{ request('q') }}',
        pageLoaded: true,
    }"
    class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors">Products</a>
                @if(isset($category))
                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                @endif
            </nav>

            <div class="mb-5">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    {{ isset($category) ? $category->name : 'All Products' }}
                </h1>
                @if(isset($category) && $category->description)
                    <p class="text-gray-500 mt-1.5 text-sm max-w-2xl">{{ $category->description }}</p>
                @endif
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('products.index') }}" class="relative">
                <div class="flex gap-2.5">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" x-model="searchQuery" placeholder="Search products, brands, categories..."
                               class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="hidden sm:inline">Search</span>
                    </button>
                </div>

                {{-- Active Filters --}}
                @php
                    $activeFilters = collect();
                    if (request('q')) $activeFilters->push(['label' => '"' . request('q') . '"', 'query' => ['q' => null]]);
                    if (request('category')) $activeFilters->push(['label' => 'Category: ' . request('category'), 'query' => ['category' => null]]);
                    if (request('brand')) $activeFilters->push(['label' => 'Brand: ' . request('brand'), 'query' => ['brand' => null]]);
                    if (request('fabric')) $activeFilters->push(['label' => 'Fabric: ' . request('fabric'), 'query' => ['fabric' => null]]);
                    if (request('color')) $activeFilters->push(['label' => 'Color: ' . request('color'), 'query' => ['color' => null]]);
                    if (request('min_price') || request('max_price'))
                        $activeFilters->push([
                            'label' => 'Price: ' . (request('min_price') ? '$' . request('min_price') : '$0') . ' - ' . (request('max_price') ? '$' . request('max_price') : 'Any'),
                            'query' => ['min_price' => null, 'max_price' => null]
                        ]);
                    if (request('in_stock')) $activeFilters->push(['label' => 'In Stock', 'query' => ['in_stock' => null]]);
                    if (request('discounted')) $activeFilters->push(['label' => 'Discounted', 'query' => ['discounted' => null]]);
                @endphp
                @if($activeFilters->count() > 0)
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span class="text-xs text-gray-400 font-medium">Filters:</span>
                        @foreach($activeFilters as $filter)
                            <a href="{{ request()->fullUrlWithQuery(array_merge($filter['query'], ['page' => null])) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-full border border-indigo-100 hover:bg-indigo-100 transition-colors group">
                                {{ $filter['label'] }}
                                <svg class="w-3 h-3 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endforeach
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                            Clear All
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex gap-6">

            {{-- Desktop Sidebar Filters --}}
            <aside class="hidden lg:block w-64 shrink-0">
                <div class="sticky top-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Filters</h2>
                        <a href="{{ route('products.index') }}"
                           class="text-xs font-medium text-indigo-600 hover:text-indigo-700 {{ request()->anyFilled(['q','category','brand','min_price','max_price','fabric','color','in_stock','discounted']) ? '' : 'opacity-0 pointer-events-none' }}">
                            Reset
                        </a>
                    </div>

                    {{-- Category --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Category</h3>
                        </div>
                        <div class="px-4 py-2 space-y-0.5 max-h-60 overflow-y-auto">
                            <a href="{{ route('products.index') . (request()->except(['category', 'page']) ? '?' . http_build_query(request()->except(['category', 'page'])) : '') }}"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ !request('category') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ !request('category') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                    @if(!request('category'))
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </span>
                                All Categories
                            </a>
                            @foreach($categories ?? [] as $cat)
                                <a href="{{ route('products.index', ['category' => $cat->slug] + request()->except(['category', 'page'])) }}"
                                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request('category') === $cat->slug ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('category') === $cat->slug ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                        @if(request('category') === $cat->slug)
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brand --}}
                    @if(isset($brands) && $brands->count() > 0)
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <h3 class="text-sm font-semibold text-gray-900">Brand</h3>
                            </div>
                            <div class="px-4 py-2 space-y-0.5 max-h-52 overflow-y-auto">
                                <a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}"
                                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ !request('brand') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ !request('brand') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                        @if(!request('brand'))
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                    All Brands
                                </a>
                                @foreach($brands as $brand)
                                    <a href="{{ request()->fullUrlWithQuery(['brand' => $brand, 'page' => null]) }}"
                                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request('brand') === $brand ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('brand') === $brand ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                            @if(request('brand') === $brand)
                                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </span>
                                        {{ $brand }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Price Range --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Price Range</h3>
                        </div>
                        <div class="px-4 py-4">
                            <form method="GET" action="{{ route('products.index') }}">
                                @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $v)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                               class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                                    </div>
                                    <span class="text-gray-300 text-xs">—</span>
                                    <div class="relative flex-1">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                               class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                                    </div>
                                </div>
                                <button type="submit"
                                        class="w-full mt-3 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                                    Apply
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Quick Filters --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Quick Filters</h3>
                        </div>
                        <div class="px-4 py-3 space-y-2">
                            <a href="{{ request()->fullUrlWithQuery(['in_stock' => request('in_stock') ? null : 1, 'page' => null]) }}"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request('in_stock') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('in_stock') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                    @if(request('in_stock'))
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </span>
                                In Stock Only
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['discounted' => request('discounted') ? null : 1, 'page' => null]) }}"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request('discounted') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('discounted') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                    @if(request('discounted'))
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </span>
                                Discounted Only
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Product Area --}}
            <main class="flex-1 min-w-0">
                {{-- Top Bar --}}
                <div class="flex items-center justify-between gap-4 mb-4 bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
                    <div class="flex items-center gap-3">
                        <button type="button" x-on:click="mobileFiltersOpen = true"
                                class="lg:hidden relative px-3.5 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filters
                            @if($activeFilters->count() > 0)
                                <span class="bg-indigo-600 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center shadow-sm">{{ $activeFilters->count() }}</span>
                            @endif
                        </button>
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold text-gray-900">{{ number_format($products->total()) }}</span>
                            <span class="hidden sm:inline"> product{{ $products->total() !== 1 ? 's' : '' }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <label class="text-sm text-gray-400 hidden sm:inline">Sort:</label>
                        <select name="sort" onchange="window.location.href=this.value"
                                class="text-sm border border-gray-200 rounded-lg px-3 py-2 pr-7 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none bg-white transition-all appearance-none cursor-pointer"
                                style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%239CA3AF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E\"); background-position: right 6px center; background-repeat: no-repeat; background-size: 16px;">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest', 'page' => null]) }}" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc', 'page' => null]) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc', 'page' => null]) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc', 'page' => null]) }}" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc', 'page' => null]) }}" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                        </select>
                    </div>
                </div>

                {{-- Products Grid --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16 bg-white rounded-xl border border-gray-100 shadow-sm">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 mb-5">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1.5">No products found</h3>
                        <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">
                            @if(request()->anyFilled(['q','category','brand','min_price','max_price','fabric','color','in_stock','discounted']))
                                We couldn't find any products matching your filters. Try adjusting or clearing them.
                            @else
                                This category doesn't have any products yet. Check back soon.
                            @endif
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            @if(request()->anyFilled(['q','category','brand','min_price','max_price','fabric','color','in_stock','discounted']))
                                <a href="{{ route('products.index') }}"
                                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                                    Reset Filters
                                </a>
                            @endif
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                                View All Products
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Loading Skeleton --}}
                <div x-show="!pageLoaded" x-cloak class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                    @for($i = 0; $i < 8; $i++)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden animate-pulse">
                            <div class="aspect-square bg-gray-100"></div>
                            <div class="p-4 space-y-2.5">
                                <div class="flex gap-2">
                                    <div class="h-3 w-16 bg-gray-100 rounded"></div>
                                    <div class="h-3 w-12 bg-gray-100 rounded"></div>
                                </div>
                                <div class="h-4 w-3/4 bg-gray-100 rounded"></div>
                                <div class="h-3 w-1/2 bg-gray-100 rounded"></div>
                                <div class="flex items-center justify-between pt-1">
                                    <div class="h-5 w-20 bg-gray-100 rounded"></div>
                                    <div class="h-4 w-16 bg-gray-100 rounded-full"></div>
                                </div>
                                <div class="h-9 w-full bg-gray-100 rounded-lg mt-2"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile Filter Overlay --}}
    <div x-show="mobileFiltersOpen"
         x-cloak
         x-transition:enter="transition-all duration-300 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-all duration-200 ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm lg:hidden"
         x-on:click="mobileFiltersOpen = false">
    </div>

    <div x-show="mobileFiltersOpen"
         x-cloak
         x-transition:enter="transition-all duration-300 ease-out"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition-all duration-200 ease-in"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 bottom-0 z-50 w-full max-w-sm bg-white shadow-2xl lg:hidden overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-4 py-3.5 flex items-center justify-between z-10">
            <h2 class="text-base font-semibold text-gray-900">Filters</h2>
            <button type="button" x-on:click="mobileFiltersOpen = false"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-4 space-y-5">
            {{-- Category --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Category</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index') . (request()->except(['category', 'page']) ? '?' . http_build_query(request()->except(['category', 'page'])) : '') }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                       x-on:click="mobileFiltersOpen = false">All</a>
                    @foreach($categories ?? [] as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug] + request()->except(['category', 'page'])) }}"
                           class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request('category') === $cat->slug ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                           x-on:click="mobileFiltersOpen = false">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Brand --}}
            @if(isset($brands) && $brands->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Brand</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}"
                           class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ !request('brand') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                           x-on:click="mobileFiltersOpen = false">All</a>
                        @foreach($brands as $brand)
                            <a href="{{ request()->fullUrlWithQuery(['brand' => $brand, 'page' => null]) }}"
                               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request('brand') === $brand ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                               x-on:click="mobileFiltersOpen = false">{{ $brand }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quick Filters --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Filters</h4>
                <div class="space-y-2">
                    <a href="{{ request()->fullUrlWithQuery(['in_stock' => request('in_stock') ? null : 1, 'page' => null]) }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all {{ request('in_stock') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                       x-on:click="mobileFiltersOpen = false">
                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('in_stock') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                            @if(request('in_stock'))
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </span>
                        In Stock Only
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['discounted' => request('discounted') ? null : 1, 'page' => null]) }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all {{ request('discounted') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                       x-on:click="mobileFiltersOpen = false">
                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('discounted') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                            @if(request('discounted'))
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </span>
                        Discounted Only
                    </a>
                </div>
            </div>

            {{-- Price Range --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Price Range</h4>
                <form method="GET" action="{{ route('products.index') }}" x-on:submit="mobileFiltersOpen = false">
                    @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                   class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none">
                        </div>
                        <span class="text-gray-300 text-xs">—</span>
                        <div class="relative flex-1">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                   class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full mt-3 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                        Apply
                    </button>
                </form>
            </div>

            {{-- Fabric --}}
            @if(isset($fabrics) && $fabrics->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Fabric</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['fabric' => null, 'page' => null]) }}"
                           class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ !request('fabric') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                           x-on:click="mobileFiltersOpen = false">All</a>
                        @foreach($fabrics as $fabric)
                            <a href="{{ request()->fullUrlWithQuery(['fabric' => $fabric, 'page' => null]) }}"
                               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request('fabric') === $fabric ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                               x-on:click="mobileFiltersOpen = false">{{ $fabric }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Color --}}
            @if(isset($colors) && $colors->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Color</h4>
                    <div class="flex flex-wrap gap-2.5">
                        <a href="{{ request()->fullUrlWithQuery(['color' => null, 'page' => null]) }}"
                           class="w-8 h-8 rounded-full border-2 {{ !request('color') ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-gray-300' }} flex items-center justify-center bg-white transition-all"
                           title="All Colors" x-on:click="mobileFiltersOpen = false">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                        @foreach($colors as $color)
                            @php
                                $colorMap = [
                                    'White' => '#ffffff', 'Black' => '#1f2937', 'Red' => '#ef4444',
                                    'Blue' => '#3b82f6', 'Green' => '#10b981', 'Yellow' => '#facc15',
                                    'Pink' => '#ec4899', 'Purple' => '#8b5cf6', 'Gray' => '#9ca3af',
                                    'Brown' => '#92400e', 'Orange' => '#f97316', 'Navy' => '#1e3a5f',
                                    'Beige' => '#f5f5dc', 'Cream' => '#fffdd0', 'Maroon' => '#800000',
                                    'Teal' => '#0d9488', 'Coral' => '#ff7f50', 'Mint' => '#98ff98',
                                    'Lavender' => '#e6e6fa', 'Charcoal' => '#36454f',
                                ];
                                $bgColor = $colorMap[$color] ?? '#e5e7eb';
                                $isDark = in_array($color, ['Black', 'Navy', 'Charcoal', 'Maroon', 'Purple']);
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['color' => $color, 'page' => null]) }}"
                               class="w-8 h-8 rounded-full border-2 {{ request('color') === $color ? 'border-indigo-500 ring-2 ring-indigo-200 scale-110' : 'border-gray-200 hover:border-gray-300 hover:scale-105' }} transition-all duration-200"
                               title="{{ $color }}" style="background-color: {{ $bgColor }};"
                               x-on:click="mobileFiltersOpen = false">
                                @if(request('color') === $color)
                                    <svg class="w-3 h-3 {{ $isDark ? 'text-white' : 'text-indigo-600' }} mx-auto mt-[6px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Mobile Filters Footer --}}
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-3.5 flex gap-3">
            <a href="{{ route('products.index') }}"
               class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                Clear All
            </a>
            <button type="button" x-on:click="mobileFiltersOpen = false"
                    class="flex-1 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                Show Results
            </button>
        </div>
    </div>
</div>

{{-- Error State --}}
<div x-data="{ hasError: false }"
     x-init="window.addEventListener('load', function() {
         document.querySelectorAll('.product-card').length === 0 && !document.querySelector('[class*=py-16]') && (hasError = true);
     })">
    <div x-show="hasError" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="text-center max-w-md mx-auto p-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-50 mb-5">
                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1.5">Something went wrong</h3>
            <p class="text-gray-400 text-sm mb-6">We couldn't load the products. Please try again.</p>
            <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Retry
            </button>
        </div>
    </div>
</div>
@endsection
