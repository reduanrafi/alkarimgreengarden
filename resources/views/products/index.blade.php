@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - ' . config('app.name') : 'Products - ' . config('app.name'))

@php
    $initialFilters = [
        'category' => request('category', ''),
        'brand' => request('brand', ''),
        'fabric' => request('fabric', ''),
        'color' => request('color', ''),
        'min_price' => request('min_price', ''),
        'max_price' => request('max_price', ''),
        'in_stock' => request('in_stock', ''),
        'discounted' => request('discounted', ''),
    ];
@endphp

@section('content')
<div
    x-data="productCatalog(@js([
        'q' => request('q', ''),
        'filters' => $initialFilters,
        'sort' => request('sort', 'latest'),
        'page' => $products->currentPage(),
        'total' => $products->total(),
        'baseUrl' => url()->current(),
        'categoryLabels' => ($categories ?? collect())->pluck('name', 'slug')->toArray(),
    ]))"
    class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#1f5c3f] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('products.index') }}" class="hover:text-[#1f5c3f] transition-colors">Products</a>
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
            <form method="GET" action="{{ route('products.index') }}" x-on:submit.prevent="fetchProducts()" class="relative">
                <div class="flex gap-2.5">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" x-model="q"
                               x-on:keydown.enter.prevent="q = $event.target.value; fetchProducts()"
                               x-on:input.debounce.500ms="q = $event.target.value; page = 1; fetchProducts()"
                               placeholder="Search products, brands, categories..."
                               class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#3f8a5c] focus:ring-2 focus:ring-[#d5e6d5] outline-none transition-all">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-[#1f5c3f] text-white text-sm font-semibold rounded-xl hover:bg-[#173d2b] transition-all shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="hidden sm:inline">Search</span>
                    </button>
                </div>

                {{-- Active Filters (reactive) --}}
                <div x-show="activeFilters.length > 0" class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="text-xs text-gray-400 font-medium">Filters:</span>
                    <template x-for="filter in activeFilters" :key="filter.key">
                        <button type="button" x-on:click="removeFilter(filter.key)"
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#e4efe4] text-[#1f5c3f] text-xs font-medium rounded-full border border-[#d5e6d5] hover:bg-[#d5e6d5] transition-colors group">
                            <span x-text="filter.label"></span>
                            <svg class="w-3 h-3 text-[#3f8a5c] group-hover:text-[#1f5c3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </template>
                    <button type="button" x-on:click="clearFilters()"
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                        Clear All
                    </button>
                </div>
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
                        <button type="button" x-on:click="clearFilters()"
                                class="text-xs font-medium text-[#1f5c3f] hover:text-[#173d2b]">
                            Reset
                        </button>
                    </div>

                    {{-- Category --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Category</h3>
                        </div>
                        <div class="px-4 py-2 space-y-0.5 max-h-60 overflow-y-auto">
                            <button type="button" x-on:click="setFilter('category', '')"
                                    class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all w-full text-left"
                                    :class="!filters.category ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="!filters.category ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                                    <svg x-show="!filters.category" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                All Categories
                            </button>
                            @foreach($categories ?? [] as $cat)
                                <button type="button" x-on:click="setFilter('category', '{{ $cat->slug }}')"
                                        class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all w-full text-left"
                                        :class="filters.category === '{{ $cat->slug }}' ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                                    <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="filters.category === '{{ $cat->slug }}' ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                                        <svg x-show="filters.category === '{{ $cat->slug }}'" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    {{ $cat->name }}
                                </button>
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
                                <button type="button" x-on:click="setFilter('brand', '')"
                                        class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all w-full text-left"
                                        :class="!filters.brand ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                                    <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="!filters.brand ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                                        <svg x-show="!filters.brand" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    All Brands
                                </button>
                                @foreach($brands as $brand)
                                    <button type="button" x-on:click="setFilter('brand', '{{ $brand }}')"
                                            class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all w-full text-left"
                                            :class="filters.brand === '{{ $brand }}' ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="filters.brand === '{{ $brand }}' ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                                            <svg x-show="filters.brand === '{{ $brand }}'" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        {{ $brand }}
                                    </button>
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
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                    <input type="number" x-model.number="filters.min_price" placeholder="Min"
                                           class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-[#3f8a5c] focus:ring-2 focus:ring-[#d5e6d5] outline-none transition-all">
                                </div>
                                <span class="text-gray-300 text-xs">â€”</span>
                                <div class="relative flex-1">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                                    <input type="number" x-model.number="filters.max_price" placeholder="Max"
                                           class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-[#3f8a5c] focus:ring-2 focus:ring-[#d5e6d5] outline-none transition-all">
                                </div>
                            </div>
                            <button type="button" x-on:click="applyPrice()"
                                    class="w-full mt-3 px-4 py-2 bg-[#1f5c3f] text-white text-sm font-semibold rounded-lg hover:bg-[#173d2b] transition-all shadow-sm">
                                Apply
                            </button>
                        </div>
                    </div>

                    {{-- Quick Filters --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Quick Filters</h3>
                        </div>
                        <div class="px-4 py-3 space-y-2">
                            <button type="button" x-on:click="toggleBool('in_stock')"
                                    class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all w-full text-left"
                                    :class="filters.in_stock ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="filters.in_stock ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                                    <svg x-show="filters.in_stock" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                In Stock Only
                            </button>
                            <button type="button" x-on:click="toggleBool('discounted')"
                                    class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all w-full text-left"
                                    :class="filters.discounted ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="filters.discounted ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                                    <svg x-show="filters.discounted" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Discounted Only
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Product Area --}}
            <main class="flex-1 min-w-0">
                {{-- Top Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4 bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
                    <div class="flex items-center gap-3">
                        <button type="button" x-on:click="mobileFiltersOpen = true"
                                class="lg:hidden relative px-3.5 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filters
                            <span x-show="activeFilters.length > 0" x-cloak class="bg-[#1f5c3f] text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center shadow-sm" x-text="activeFilters.length"></span>
                        </button>
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold text-gray-900" x-text="total"></span>
                            <span class="hidden sm:inline" x-text="total === 1 ? ' product' : ' products'"></span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2.5 ml-auto">
                        <label class="text-sm text-gray-400 hidden sm:inline">Sort:</label>
                        <select x-model="sort" x-on:change="page = 1; fetchProducts()"
                                class="text-sm border border-gray-200 rounded-lg px-3 py-2 pr-7 focus:border-[#3f8a5c] focus:ring-2 focus:ring-[#d5e6d5] outline-none bg-white transition-all appearance-none cursor-pointer"
                                style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%239CA3AF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E\"); background-position: right 6px center; background-repeat: no-repeat; background-size: 16px;">
                            <option value="latest">Newest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="best_selling">Best Selling</option>
                            <option value="popular">Most Popular</option>
                        </select>
                    </div>
                </div>

                {{-- Catalog (swappable via AJAX) --}}
                <div x-ref="catalog" x-show="!loading && !error">
                    @include('products.partials.catalog', ['products' => $products])
                </div>

                {{-- Loading Skeleton --}}
                <div x-show="loading" x-cloak class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                    <x-skeletons.product-card :count="8" />
                </div>

                {{-- Error State --}}
                <div x-show="error" x-cloak class="text-center py-16 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-50 mb-5">
                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1.5">Something went wrong</h3>
                    <p class="text-gray-400 text-sm mb-6">We couldn't load the products. Please try again.</p>
                    <button type="button" x-on:click="fetchProducts()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#1f5c3f] text-white text-sm font-semibold rounded-lg hover:bg-[#173d2b] transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Retry
                    </button>
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
                    <button type="button" x-on:click="setFilter('category', '')"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                            :class="!filters.category ? 'bg-[#1f5c3f] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">All</button>
                    @foreach($categories ?? [] as $cat)
                        <button type="button" x-on:click="setFilter('category', '{{ $cat->slug }}')"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                                :class="filters.category === '{{ $cat->slug }}' ? 'bg-[#1f5c3f] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Brand --}}
            @if(isset($brands) && $brands->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Brand</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" x-on:click="setFilter('brand', '')"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                                :class="!filters.brand ? 'bg-[#1f5c3f] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">All</button>
                        @foreach($brands as $brand)
                            <button type="button" x-on:click="setFilter('brand', '{{ $brand }}')"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                                    :class="filters.brand === '{{ $brand }}' ? 'bg-[#1f5c3f] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">{{ $brand }}</button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quick Filters --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Filters</h4>
                <div class="space-y-2">
                    <button type="button" x-on:click="toggleBool('in_stock')"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all w-full text-left"
                            :class="filters.in_stock ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="filters.in_stock ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                            <svg x-show="filters.in_stock" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        In Stock Only
                    </button>
                    <button type="button" x-on:click="toggleBool('discounted')"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all w-full text-left"
                            :class="filters.discounted ? 'bg-[#e4efe4] text-[#1f5c3f] font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center" :class="filters.discounted ? 'border-[#1f5c3f] bg-[#1f5c3f]' : 'border-gray-300'">
                            <svg x-show="filters.discounted" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        Discounted Only
                    </button>
                </div>
            </div>

            {{-- Price Range --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Price Range</h4>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                        <input type="number" x-model.number="filters.min_price" placeholder="Min"
                               class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-[#3f8a5c] focus:ring-2 focus:ring-[#d5e6d5] outline-none">
                    </div>
                    <span class="text-gray-300 text-xs">â€”</span>
                    <div class="relative flex-1">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                        <input type="number" x-model.number="filters.max_price" placeholder="Max"
                               class="w-full pl-6 pr-2.5 py-2 text-sm border border-gray-200 rounded-lg focus:border-[#3f8a5c] focus:ring-2 focus:ring-[#d5e6d5] outline-none">
                    </div>
                </div>
                <button type="button" x-on:click="applyPrice()"
                        class="w-full mt-3 px-4 py-2 bg-[#1f5c3f] text-white text-sm font-semibold rounded-lg hover:bg-[#173d2b] transition-all shadow-sm">
                    Apply
                </button>
            </div>

            {{-- Light --}}
            @if(isset($fabrics) && $fabrics->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Light</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" x-on:click="setFilter('fabric', '')"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                                :class="!filters.fabric ? 'bg-[#1f5c3f] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">All</button>
                        @foreach($fabrics as $fabric)
                            <button type="button" x-on:click="setFilter('fabric', '{{ $fabric }}')"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                                    :class="filters.fabric === '{{ $fabric }}' ? 'bg-[#1f5c3f] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">{{ $fabric }}</button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Color --}}
            @if(isset($colors) && $colors->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Color</h4>
                    <div class="flex flex-wrap gap-2.5">
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
                            $isDarkColors = ['Black', 'Navy', 'Charcoal', 'Maroon', 'Purple'];
                        @endphp
                        <button type="button" x-on:click="setFilter('color', '')"
                                class="w-8 h-8 rounded-full border-2 border-gray-200 hover:border-gray-300 flex items-center justify-center bg-white transition-all"
                                title="All Colors">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        @foreach($colors as $color)
                            <button type="button" x-on:click="setFilter('color', '{{ $color }}')"
                                    class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                                    :class="filters.color === '{{ $color }}' ? 'border-[#1f5c3f] ring-2 ring-[#d5e6d5] scale-110' : 'border-gray-200 hover:border-gray-300 hover:scale-105'"
                                    title="{{ $color }}"
                                    style="background-color: {{ $colorMap[$color] ?? '#e5e7eb' }};">
                                <svg x-show="filters.color === '{{ $color }}'" class="w-3 h-3 {{ in_array($color, $isDarkColors) ? 'text-white' : 'text-[#1f5c3f]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Mobile Filters Footer --}}
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-3.5 flex gap-3">
            <button type="button" x-on:click="clearFilters()"
                    class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                Clear All
            </button>
            <button type="button" x-on:click="mobileFiltersOpen = false"
                    class="flex-1 px-4 py-2.5 bg-[#1f5c3f] text-white text-sm font-semibold rounded-lg hover:bg-[#173d2b] transition-all shadow-sm">
                Show Results
            </button>
        </div>
    </div>
</div>

<x-product-preview />
@endsection
