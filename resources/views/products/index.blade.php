@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - ' . config('app.name') : 'Products - ' . config('app.name'))

@section('content')
<div
    x-data="{
        mobileFiltersOpen: false,
        viewMode: localStorage.getItem('productViewMode') || 'grid',
        searchQuery: '{{ request('q') }}',
        searching: false,
        loading: false,
        init() {
            this.$watch('viewMode', val => localStorage.setItem('productViewMode', val));
        },
        submitSearch() {
            if (this.$refs.searchForm) this.$refs.searchForm.submit();
        },
        handleFilterClick() {
            this.loading = true;
        }
    }"
    class="min-h-screen bg-gray-50">

    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-4" itemscope itemtype="https://schema.org/BreadcrumbList">
                <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors" itemprop="item">
                        <span itemprop="name" class="sr-only">Home</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </a>
                    <meta itemprop="position" content="1">
                </span>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors" itemprop="item">
                        <span itemprop="name">Products</span>
                    </a>
                    <meta itemprop="position" content="2">
                </span>
                @if(isset($category))
                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span class="text-gray-900 font-medium" itemprop="name">{{ $category->name }}</span>
                        <meta itemprop="position" content="3">
                    </span>
                @endif
            </nav>

            {{-- Title & Description --}}
            <div class="mb-5">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 font-serif tracking-tight">
                    {{ isset($category) ? $category->name : 'All Products' }}
                </h1>
                @if(isset($category) && $category->description)
                    <p class="text-gray-500 mt-2 max-w-2xl">{{ $category->description }}</p>
                @endif
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('products.index') }}" x-ref="searchForm" class="relative">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-5 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="q"
                            x-model="searchQuery"
                            x-on:input.debounce.400ms="searching = true; submitSearch()"
                            x-on:keydown.enter="searching = true; submitSearch()"
                            placeholder="Search by product name, brand, fabric, color..."
                            class="w-full pl-12 pr-12 py-3.5 text-base rounded-2xl border-2 border-gray-100 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200 placeholder:text-gray-400">

                        {{-- Loading Indicator --}}
                        <div x-show="searching" x-cloak class="absolute right-5 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="px-8 py-3.5 bg-indigo-600 text-white font-semibold rounded-2xl hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md active:scale-[0.98] flex items-center gap-2.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="hidden sm:inline">Search</span>
                    </button>
                </div>

                {{-- Active Filter Tags --}}
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
                @endphp
                @if($activeFilters->count() > 0)
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span class="text-xs text-gray-400 font-medium">Active Filters:</span>
                        @foreach($activeFilters as $filter)
                            <a href="{{ request()->fullUrlWithQuery($filter['query']) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-full border border-indigo-100 hover:bg-indigo-100 transition-colors group">
                                {{ $filter['label'] }}
                                <svg class="w-3 h-3 text-indigo-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endforeach
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                            Clear All
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex gap-8 relative">

            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:block w-72 shrink-0">
                <div class="sticky top-6 space-y-5">
                    {{-- Filters Header --}}
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Filters</h2>
                        <a href="{{ route('products.index') }}"
                           class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors {{ request()->anyFilled(['q','category','brand','min_price','max_price','fabric','color']) ? '' : 'opacity-0 pointer-events-none' }}">
                            Reset All
                        </a>
                    </div>

                    {{-- Category --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Category</h3>
                        </div>
                        <div class="px-5 py-3 space-y-0.5 max-h-64 overflow-y-auto custom-scrollbar">
                            <a href="{{ route('products.index') . (request()->except(['category', 'page']) ? '?' . http_build_query(request()->except(['category', 'page'])) : '') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ !request('category') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ !request('category') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                    @if(!request('category'))
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </span>
                                All Categories
                            </a>
                            @foreach($categories ?? [] as $cat)
                                <a href="{{ route('products.index', ['category' => $cat->slug] + request()->except(['category', 'page'])) }}"
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ request('category') === $cat->slug ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
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
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-50">
                                <h3 class="text-sm font-semibold text-gray-900">Brand</h3>
                            </div>
                            <div class="px-5 py-3 space-y-0.5 max-h-56 overflow-y-auto custom-scrollbar">
                                <a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}"
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ !request('brand') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ !request('brand') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                        @if(!request('brand'))
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                    All Brands
                                </a>
                                @foreach($brands as $brand)
                                    <a href="{{ request()->fullUrlWithQuery(['brand' => $brand, 'page' => null]) }}"
                                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ request('brand') === $brand ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Price Range</h3>
                        </div>
                        <div class="px-5 py-4">
                            <form method="GET" action="{{ route('products.index') }}" x-on:submit="handleFilterClick">
                                @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $v)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2.5">
                                    <div class="relative flex-1">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">$</span>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                               class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all">
                                    </div>
                                    <span class="text-gray-300 text-xs font-medium">—</span>
                                    <div class="relative flex-1">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">$</span>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                               class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all">
                                    </div>
                                </div>
                                <button type="submit"
                                        class="w-full mt-3 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all active:scale-[0.98] shadow-sm">
                                    Apply Price
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Fabric --}}
                    @if(isset($fabrics) && $fabrics->count() > 0)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-50">
                                <h3 class="text-sm font-semibold text-gray-900">Fabric</h3>
                            </div>
                            <div class="px-5 py-3 space-y-0.5 max-h-48 overflow-y-auto custom-scrollbar">
                                <a href="{{ request()->fullUrlWithQuery(['fabric' => null, 'page' => null]) }}"
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ !request('fabric') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                    <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ !request('fabric') ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                        @if(!request('fabric'))
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                    All Fabrics
                                </a>
                                @foreach($fabrics as $fabric)
                                    <a href="{{ request()->fullUrlWithQuery(['fabric' => $fabric, 'page' => null]) }}"
                                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ request('fabric') === $fabric ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                        <span class="w-4 h-4 rounded border-2 flex items-center justify-center {{ request('fabric') === $fabric ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                                            @if(request('fabric') === $fabric)
                                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </span>
                                        {{ $fabric }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Color --}}
                    @if(isset($colors) && $colors->count() > 0)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-50">
                                <h3 class="text-sm font-semibold text-gray-900">Color</h3>
                            </div>
                            <div class="px-5 py-4">
                                <div class="flex flex-wrap gap-2.5">
                                    <a href="{{ request()->fullUrlWithQuery(['color' => null, 'page' => null]) }}"
                                       class="w-9 h-9 rounded-full border-2 {{ !request('color') ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-gray-300' }} flex items-center justify-center text-[10px] font-semibold text-gray-400 bg-white transition-all"
                                       title="All Colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                                           class="w-9 h-9 rounded-full border-2 {{ request('color') === $color ? 'border-indigo-500 ring-2 ring-indigo-200 scale-110' : 'border-gray-200 hover:border-gray-300 hover:scale-105' }} transition-all duration-200"
                                           title="{{ $color }}"
                                           style="background-color: {{ $bgColor }};">
                                            @if(request('color') === $color)
                                                <svg class="w-4 h-4 {{ $isDark ? 'text-white' : 'text-indigo-600' }} mx-auto mt-[5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </aside>

            {{-- Product Area --}}
            <main class="flex-1 min-w-0">
                {{-- Top Bar --}}
                <div class="flex items-center justify-between gap-4 mb-6 bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-3">
                    <div class="flex items-center gap-3">
                        {{-- Mobile Filter Button --}}
                        <button type="button" x-on:click="mobileFiltersOpen = true"
                                class="lg:hidden relative px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filters
                            @php $activeCount = $activeFilters->count(); @endphp
                            @if($activeCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-indigo-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">{{ $activeCount }}</span>
                            @endif
                        </button>

                        {{-- Product Count --}}
                        <p class="text-sm text-gray-500">
                            <span class="font-semibold text-gray-900">{{ number_format($products->total()) }}</span>
                            <span class="hidden sm:inline"> product{{ $products->total() !== 1 ? 's' : '' }} found</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Grid/List Toggle --}}
                        <div class="hidden sm:flex items-center bg-gray-100 rounded-xl p-0.5">
                            <button type="button" x-on:click="viewMode = 'grid'"
                                    class="p-2 rounded-lg transition-all duration-200"
                                    :class="viewMode === 'grid' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400 hover:text-gray-600'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </button>
                            <button type="button" x-on:click="viewMode = 'list'"
                                    class="p-2 rounded-lg transition-all duration-200"
                                    :class="viewMode === 'list' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400 hover:text-gray-600'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </button>
                        </div>

                        {{-- Sort --}}
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-400 hidden sm:inline font-medium">Sort:</label>
                            <select name="sort" onchange="window.location.href=this.value"
                                    class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 pr-8 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none bg-white transition-all appearance-none cursor-pointer"
                                    style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%239CA3AF' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E\"); background-position: right 8px center; background-repeat: no-repeat; background-size: 20px;">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest', 'page' => null]) }}" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc', 'page' => null]) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc', 'page' => null]) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc', 'page' => null]) }}" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc', 'page' => null]) }}" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Loading Overlay --}}
                <div x-show="loading" x-cloak x-transition.opacity.duration.200ms
                     class="fixed inset-0 z-40 flex items-center justify-center bg-white/60 backdrop-blur-sm"
                     style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;">
                    <div class="bg-white rounded-2xl shadow-xl px-8 py-6 flex items-center gap-4 border border-gray-100">
                        <svg class="animate-spin h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Updating products...</span>
                    </div>
                </div>

                {{-- Products --}}
                <div x-show="!loading" x-cloak>
                    @if($products->count() > 0)
                        {{-- Grid View --}}
                        <div x-show="viewMode === 'grid'"
                             class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                            @foreach($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>

                        {{-- List View --}}
                        <div x-show="viewMode === 'list'"
                             class="space-y-4">
                            @foreach($products as $product)
                                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 overflow-hidden"
                                     data-id="{{ $product->id }}"
                                     data-name="{{ $product->name }}"
                                     data-price="{{ number_format($product->price, 2) }}"
                                     data-discount="{{ $product->discount_price ? number_format($product->discount_price, 2) : '' }}"
                                     data-price-formatted="{{ formatPrice($product->price) }}"
                                     data-discount-formatted="{{ $product->discount_price ? formatPrice($product->discount_price) : '' }}"
                                     data-category-name="{{ $product->category->name ?? '' }}"
                                     data-category-slug="{{ $product->category->slug ?? '' }}"
                                     data-fabric="{{ $product->fabric }}"
                                     data-color="{{ $product->color }}"
                                     data-print="{{ $product->print }}"
                                     data-size="{{ $product->size }}"
                                     data-stock="{{ $product->stock }}"
                                     data-description="{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 200) }}"
                                     data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                     data-slug="{{ $product->slug }}"
                                     onmouseenter="showPreview(this)"
                                     onmouseleave="onCardLeave(this)">
                                    <a href="{{ route('products.show', $product->slug) }}" class="flex flex-col sm:flex-row">
                                        {{-- Image --}}
                                        <div class="sm:w-52 shrink-0 image-zoom aspect-square sm:aspect-auto sm:h-52 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-6 relative">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                     class="w-full h-full object-contain" loading="lazy">
                                            @else
                                                <div class="text-5xl select-none transition-transform duration-500 group-hover:scale-110">
                                                    @switch($product->category->slug ?? '')
                                                        @case('mens-t-shirt') 👕 @break
                                                        @case('womens-t-shirt') 👚 @break
                                                        @case('bags') 👜 @break
                                                        @default ✨
                                                    @endswitch
                                                </div>
                                            @endif
                                            @if($product->stock_status === 'out_of_stock')
                                                <div class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-10">
                                                    <span class="bg-gray-900 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">Out of Stock</span>
                                                </div>
                                            @endif
                                            <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                                                @if($product->created_at->diffInDays(now()) < 7 && $product->stock_status !== 'out_of_stock')
                                                    <span class="badge-new px-2.5 py-1 text-[11px]">New</span>
                                                @endif
                                                @if($product->discount_price && $product->stock_status !== 'out_of_stock')
                                                    @php $discountPct = $product->discount_type === 'percentage' ? round($product->discount_price) : round((1 - $product->discount_price / $product->price) * 100); @endphp
                                                    <span class="badge-discount px-2.5 py-1 text-[11px]">-{{ $discountPct }}%</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Details --}}
                                        <div class="flex-1 p-5 sm:p-6 flex flex-col justify-between">
                                            <div class="space-y-3">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            @if($product->category)
                                                                <span class="text-[10px] uppercase tracking-[0.15em] text-indigo-500 font-semibold">{{ $product->category->name }}</span>
                                                            @endif
                                                            @if($product->brand)
                                                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                                                <span class="text-[10px] text-gray-400 font-medium">{{ $product->brand }}</span>
                                                            @endif
                                                        </div>
                                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">{{ $product->name }}</h3>
                                                    </div>
                                                    <div class="shrink-0">
                                                        @if($product->stock_status !== 'out_of_stock')
                                                            @if($product->stock_status === 'low_stock')
                                                                <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Low Stock</span>
                                                            @else
                                                                <span class="flex items-center gap-1 text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> In Stock
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($product->avg_rating > 0)
                                                    <div class="flex items-center gap-1.5">
                                                        <div class="flex text-amber-400">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-3.5 h-3.5 {{ $i <= round($product->avg_rating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-[11px] text-gray-400">({{ $product->reviews_count }})</span>
                                                    </div>
                                                @endif

                                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 150) }}
                                                </p>

                                                @if($product->fabric || $product->color || $product->size)
                                                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                                        @if($product->fabric)
                                                            <span class="inline-flex items-center gap-1"><span class="text-gray-400">Fabric:</span> {{ $product->fabric }}</span>
                                                        @endif
                                                        @if($product->color)
                                                            <span class="inline-flex items-center gap-1"><span class="text-gray-400">Color:</span> {{ $product->color }}</span>
                                                        @endif
                                                        @if($product->size)
                                                            <span class="inline-flex items-center gap-1"><span class="text-gray-400">Size:</span> {{ $product->size }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                                <div class="flex items-center gap-2.5">
                                                    @if($product->discount_price)
                                                        <span class="text-xl font-bold text-gray-900">{{ formatPrice($product->final_price) }}</span>
                                                        <span class="text-sm text-gray-400 line-through">{{ formatPrice($product->price) }}</span>
                                                    @else
                                                        <span class="text-xl font-bold text-gray-900">{{ formatPrice($product->price) }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    @auth
                                                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" onclick="event.stopPropagation()">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="p-2.5 rounded-xl border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 transition-all"
                                                                    title="{{ $product->isInWishlist(auth()->id()) ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                                                <svg class="w-4 h-4 {{ $product->isInWishlist(auth()->id()) ? 'text-red-500 fill-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endauth
                                                    <a href="{{ route('products.show', $product->slug) }}"
                                                       class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all active:scale-[0.98] shadow-sm">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-10">
                            {{ $products->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-50 mb-6">
                                <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2 font-serif">No products found</h3>
                            <p class="text-gray-400 text-sm mb-8 max-w-md mx-auto">
                                @if(request()->anyFilled(['q','category','brand','min_price','max_price','fabric','color']))
                                    We couldn't find any products matching your current filters. Try adjusting or clearing your search criteria.
                                @else
                                    This category doesn't have any products yet. Check back soon for new arrivals.
                                @endif
                            </p>
                            <div class="flex items-center justify-center gap-3">
                                @if(request()->anyFilled(['q','category','brand','min_price','max_price','fabric','color']))
                                    <a href="{{ route('products.index') }}"
                                       class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white font-semibold rounded-2xl hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md active:scale-[0.98]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Reset Filters
                                    </a>
                                @endif
                                <a href="{{ route('products.index') }}"
                                   class="inline-flex items-center gap-2 px-8 py-3 bg-white text-gray-700 font-semibold rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                                    View All Products
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Skeleton Loading (shown when loading) --}}
                <div x-show="loading" x-cloak x-transition.opacity.duration.200ms>
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                        @for($i = 0; $i < 8; $i++)
                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-pulse">
                                <div class="aspect-square bg-gray-100"></div>
                                <div class="p-4 sm:p-5 space-y-3">
                                    <div class="flex gap-2">
                                        <div class="h-3 w-16 skeleton rounded"></div>
                                        <div class="h-3 w-12 skeleton rounded"></div>
                                    </div>
                                    <div class="h-4 w-3/4 skeleton rounded"></div>
                                    <div class="h-3 w-1/2 skeleton rounded"></div>
                                    <div class="flex items-center justify-between pt-1">
                                        <div class="h-5 w-20 skeleton rounded"></div>
                                        <div class="h-4 w-16 skeleton rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile Offcanvas Filter Overlay --}}
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
        <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
            <button type="button" x-on:click="mobileFiltersOpen = false"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 space-y-5">
            {{-- Category --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Category</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index') . (request()->except(['category', 'page']) ? '?' . http_build_query(request()->except(['category', 'page'])) : '') }}"
                       class="px-3.5 py-2 text-xs font-medium rounded-xl transition-all {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                       x-on:click="mobileFiltersOpen = false">
                        All
                    </a>
                    @foreach($categories ?? [] as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug] + request()->except(['category', 'page'])) }}"
                           class="px-3.5 py-2 text-xs font-medium rounded-xl transition-all {{ request('category') === $cat->slug ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                           x-on:click="mobileFiltersOpen = false">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Brand --}}
            @if(isset($brands) && $brands->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Brand</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}"
                           class="px-3.5 py-2 text-xs font-medium rounded-xl transition-all {{ !request('brand') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                           x-on:click="mobileFiltersOpen = false">
                            All
                        </a>
                        @foreach($brands as $brand)
                            <a href="{{ request()->fullUrlWithQuery(['brand' => $brand, 'page' => null]) }}"
                               class="px-3.5 py-2 text-xs font-medium rounded-xl transition-all {{ request('brand') === $brand ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                               x-on:click="mobileFiltersOpen = false">
                                {{ $brand }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

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
                    <div class="flex items-center gap-2.5">
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                   class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all">
                        </div>
                        <span class="text-gray-300">—</span>
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">$</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                   class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full mt-3 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all active:scale-[0.98] shadow-sm">
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
                           class="px-3.5 py-2 text-xs font-medium rounded-xl transition-all {{ !request('fabric') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                           x-on:click="mobileFiltersOpen = false">
                            All
                        </a>
                        @foreach($fabrics as $fabric)
                            <a href="{{ request()->fullUrlWithQuery(['fabric' => $fabric, 'page' => null]) }}"
                               class="px-3.5 py-2 text-xs font-medium rounded-xl transition-all {{ request('fabric') === $fabric ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                               x-on:click="mobileFiltersOpen = false">
                                {{ $fabric }}
                            </a>
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
                           class="w-9 h-9 rounded-full border-2 {{ !request('color') ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-gray-300' }} flex items-center justify-center bg-white transition-all"
                           title="All Colors"
                           x-on:click="mobileFiltersOpen = false">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                               class="w-9 h-9 rounded-full border-2 {{ request('color') === $color ? 'border-indigo-500 ring-2 ring-indigo-200 scale-110' : 'border-gray-200 hover:border-gray-300 hover:scale-105' }} transition-all duration-200"
                               title="{{ $color }}"
                               style="background-color: {{ $bgColor }};"
                               x-on:click="mobileFiltersOpen = false">
                                @if(request('color') === $color)
                                    <svg class="w-4 h-4 {{ $isDark ? 'text-white' : 'text-indigo-600' }} mx-auto mt-[5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Mobile Filters Footer --}}
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-5 py-4 flex gap-3">
            <a href="{{ route('products.index') }}"
               class="flex-1 text-center px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                Clear All
            </a>
            <button type="button" x-on:click="mobileFiltersOpen = false"
                    class="flex-1 px-4 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all active:scale-[0.98] shadow-sm">
                Show Results
            </button>
        </div>
    </div>
</div>

<x-product-preview />
@endsection
