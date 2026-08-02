@php
    $brands = \App\Services\CatalogService::brands();
    $trendingProducts = $featuredProducts->count() > 0 ? $featuredProducts : $latestProducts;
@endphp

@extends('layouts.app')

@section('title', config('app.name') . ' - Fashion & Lifestyle')
@section('meta_description', 'Discover the latest trends in fashion. Shop our collection of clothing, accessories, and more.')

@section('content')
    {{-- Hero Carousel --}}
    <x-hero :categories="$categories" :banner="$banner" :heroBanners="$heroBanners" />

    {{-- Feature Cards --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Free Shipping</p>
                    <p class="text-xs text-gray-400">On orders over $100</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Easy Returns</p>
                    <p class="text-xs text-gray-400">30-day return policy</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Secure Payment</p>
                    <p class="text-xs text-gray-400">100% secure checkout</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">24/7 Support</p>
                    <p class="text-xs text-gray-400">Dedicated assistance</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 col-span-2 md:col-span-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Cash on Delivery</p>
                    <p class="text-xs text-gray-400">Pay when you receive</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Especially For You --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 scroll-fade-in">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 bg-pink-50 text-pink-600 text-xs font-semibold rounded-full mb-3">Personalized</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-serif">Especially For You</h2>
            <p class="text-gray-400 text-sm mt-1.5">Handpicked deals crafted just for you</p>
        </div>

        @if($especiallyForYou->count() > 0)
            <div class="hidden sm:grid sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-6 gap-5">
                @foreach($especiallyForYou as $banner)
                    <a href="{{ $banner->redirect_url ?: route('products.index') }}"
                       class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer block"
                       style="height: 280px; @if($banner->image) background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center; @else background: {{ $banner->background_color ?: 'linear-gradient(145deg, #ecfdf5 0%, #a7f3d0 100%)' }}; @endif">
                        @if($banner->image)
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        @endif
                        <div class="relative h-full flex flex-col justify-between p-6 pt-14">
                            <div class="pr-14">
                                <h3 class="text-lg font-bold {{ $banner->image ? 'text-white' : 'text-gray-900' }} leading-tight">{{ $banner->title }}</h3>
                                @if($banner->short_description)
                                    <p class="text-xs {{ $banner->image ? 'text-gray-200' : 'text-gray-500' }} mt-1.5 leading-relaxed">{{ $banner->short_description }}</p>
                                @endif
                            </div>
                            @if($banner->button_text)
                                <div class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-gray-900 shadow-sm transition-all duration-300 group-hover:shadow-lg">
                                        {{ $banner->button_text }}
                                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="sm:hidden flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide -mx-4 px-4 pb-2">
                @foreach($especiallyForYou as $banner)
                    <a href="{{ $banner->redirect_url ?: route('products.index') }}"
                       class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0 block"
                       style="width: 240px; height: 260px; @if($banner->image) background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center; @else background: {{ $banner->background_color ?: 'linear-gradient(145deg, #ecfdf5 0%, #a7f3d0 100%)' }}; @endif">
                        @if($banner->image)
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        @endif
                        <div class="relative h-full flex flex-col justify-between p-5 pt-12">
                            <div class="pr-10">
                                <h3 class="text-base font-bold {{ $banner->image ? 'text-white' : 'text-gray-900' }} leading-tight">{{ $banner->title }}</h3>
                                @if($banner->short_description)
                                    <p class="text-xs {{ $banner->image ? 'text-gray-200' : 'text-gray-500' }} mt-1 leading-relaxed">{{ $banner->short_description }}</p>
                                @endif
                            </div>
                            @if($banner->button_text)
                                <div class="text-center">
                                    <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-gray-900 shadow-sm">{{ $banner->button_text }} <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
        <div class="hidden sm:grid sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-6 gap-5">
            {{-- Card 1: Light Green --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                 style="height: 280px; background: linear-gradient(145deg, #ecfdf5 0%, #a7f3d0 100%);">
                <div class="absolute top-4 right-4 w-[68px] h-[68px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-lg ring-4 ring-white/60 z-10">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-6 pt-14">
                    <div class="pr-14">
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">Order Via<br>WhatsApp</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Fast order from WhatsApp</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-emerald-700 shadow-sm transition-all duration-300 group-hover:shadow-lg group-hover:bg-emerald-50">
                            Call Now
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Sky Blue --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                 style="height: 280px; background: linear-gradient(145deg, #f0f9ff 0%, #bae6fd 100%);">
                <div class="absolute top-4 right-4 w-[68px] h-[68px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-lg ring-4 ring-white/60 z-10">
                    <svg class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-6 pt-14">
                    <div class="pr-14">
                        <h3 class="text-3xl font-black text-gray-900 leading-none">10%<br><span class="text-lg font-bold">OFF</span></h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">+ Cashback</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-sky-700 shadow-sm transition-all duration-300 group-hover:shadow-lg group-hover:bg-sky-50">
                            Shop Now
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 3: Lime Green --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                 style="height: 280px; background: linear-gradient(145deg, #f0fdf4 0%, #bbf7d0 100%);">
                <div class="absolute top-4 right-4 w-[68px] h-[68px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-lg ring-4 ring-white/60 z-10">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-6 pt-14">
                    <div class="pr-14">
                        <h3 class="text-3xl font-black text-gray-900 leading-none">14%<br><span class="text-lg font-bold">OFF</span></h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">+ Cashback</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-green-700 shadow-sm transition-all duration-300 group-hover:shadow-lg group-hover:bg-green-50">
                            Explore
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 4: Purple --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                 style="height: 280px; background: linear-gradient(145deg, #f5f3ff 0%, #ddd6fe 100%);">
                <div class="absolute top-4 right-4 w-[68px] h-[68px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-lg ring-4 ring-white/60 z-10">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-6 pt-14">
                    <div class="pr-14">
                        <h3 class="text-3xl font-black text-gray-900 leading-none">60%<br><span class="text-lg font-bold">OFF</span></h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Limited Time Offer</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-purple-700 shadow-sm transition-all duration-300 group-hover:shadow-lg group-hover:bg-purple-50">
                            View Offer
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 5: Orange --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                 style="height: 280px; background: linear-gradient(145deg, #fff7ed 0%, #fed7aa 100%);">
                <div class="absolute top-4 right-4 w-[68px] h-[68px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-lg ring-4 ring-white/60 z-10">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-6 pt-14">
                    <div class="pr-14">
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">Call To<br>Order</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Customer Support</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-orange-700 shadow-sm transition-all duration-300 group-hover:shadow-lg group-hover:bg-orange-50">
                            Call Now
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card 6: Pink / Red --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                 style="height: 280px; background: linear-gradient(145deg, #fef2f2 0%, #fecaca 100%);">
                <div class="absolute top-4 right-4 w-[68px] h-[68px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-lg ring-4 ring-white/60 z-10">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-6 pt-14">
                    <div class="pr-14">
                        <h3 class="text-3xl font-black text-gray-900 leading-none">25%<br><span class="text-lg font-bold">OFF</span></h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Special Discount</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-semibold bg-white text-red-700 shadow-sm transition-all duration-300 group-hover:shadow-lg group-hover:bg-red-50">
                            Shop Now
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sm:hidden flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide -mx-4 px-4 pb-2">
            {{-- Card 1 Mobile --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0"
                 style="width: 240px; height: 260px; background: linear-gradient(145deg, #ecfdf5 0%, #a7f3d0 100%);">
                <div class="absolute top-3 right-3 w-[60px] h-[60px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-md ring-4 ring-white/60 z-10">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-5 pt-12">
                    <div class="pr-10">
                        <h3 class="text-base font-bold text-gray-900 leading-tight">Order Via WhatsApp</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Fast order from WhatsApp</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-emerald-700 shadow-sm">Call Now <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </div>

            {{-- Card 2 Mobile --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0"
                 style="width: 240px; height: 260px; background: linear-gradient(145deg, #f0f9ff 0%, #bae6fd 100%);">
                <div class="absolute top-3 right-3 w-[60px] h-[60px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-md ring-4 ring-white/60 z-10">
                    <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-5 pt-12">
                    <div class="pr-10">
                        <h3 class="text-2xl font-black text-gray-900 leading-none">10% OFF</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">+ Cashback</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-sky-700 shadow-sm">Shop Now <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </div>

            {{-- Card 3 Mobile --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0"
                 style="width: 240px; height: 260px; background: linear-gradient(145deg, #f0fdf4 0%, #bbf7d0 100%);">
                <div class="absolute top-3 right-3 w-[60px] h-[60px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-md ring-4 ring-white/60 z-10">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-5 pt-12">
                    <div class="pr-10">
                        <h3 class="text-2xl font-black text-gray-900 leading-none">14% OFF</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">+ Cashback</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-green-700 shadow-sm">Explore <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </div>

            {{-- Card 4 Mobile --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0"
                 style="width: 240px; height: 260px; background: linear-gradient(145deg, #f5f3ff 0%, #ddd6fe 100%);">
                <div class="absolute top-3 right-3 w-[60px] h-[60px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-md ring-4 ring-white/60 z-10">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-5 pt-12">
                    <div class="pr-10">
                        <h3 class="text-2xl font-black text-gray-900 leading-none">60% OFF</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Limited Time Offer</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-purple-700 shadow-sm">View Offer <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </div>

            {{-- Card 5 Mobile --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0"
                 style="width: 240px; height: 260px; background: linear-gradient(145deg, #fff7ed 0%, #fed7aa 100%);">
                <div class="absolute top-3 right-3 w-[60px] h-[60px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-md ring-4 ring-white/60 z-10">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-5 pt-12">
                    <div class="pr-10">
                        <h3 class="text-base font-bold text-gray-900 leading-tight">Call To Order</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Customer Support</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-orange-700 shadow-sm">Call Now <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </div>

            {{-- Card 6 Mobile --}}
            <div class="group relative rounded-[22px] overflow-hidden shadow-md snap-center shrink-0"
                 style="width: 240px; height: 260px; background: linear-gradient(145deg, #fef2f2 0%, #fecaca 100%);">
                <div class="absolute top-3 right-3 w-[60px] h-[60px] rounded-full bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-md ring-4 ring-white/60 z-10">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="h-full flex flex-col justify-between p-5 pt-12">
                    <div class="pr-10">
                        <h3 class="text-2xl font-black text-gray-900 leading-none">25% OFF</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Special Discount</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-white text-red-700 shadow-sm">Shop Now <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>

    {{-- Shop by Category --}}
    <x-category-buttons :categories="$categories" />

    {{-- Promotional Banners --}}
    <x-promo-banners :carouselBanners="$carouselBanners" :fixedBanner="$fixedBanner" />

    {{-- New Arrivals --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 scroll-fade-in">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-full mb-3">Fresh</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-serif">New Arrivals</h2>
                <p class="text-gray-400 text-sm mt-1">The latest additions to our collection</p>
            </div>
            @if($latestProducts->count() > 0)
                <a href="{{ route('products.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition group">
                    View All
                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>
        @if($latestProducts->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($latestProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <x-empty-state
                icon="sparkles"
                title="New arrivals are on their way"
                message="We're refreshing our collection right now. Check back soon for the latest styles."
                :action="route('products.index')"
                actionText="Browse All Products"
            />
        @endif
        @if($latestProducts->count() > 0)
            <div class="text-center mt-10 sm:hidden">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    View All Products
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        @endif
    </section>

    {{-- Best Sellers --}}
    <section class="bg-gray-50/50 py-16 sm:py-20 scroll-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 text-xs font-semibold rounded-full mb-3">Bestselling</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-serif">Best Sellers</h2>
                    <p class="text-gray-400 text-sm mt-1">Most popular products our customers love</p>
                </div>
                @if(isset($bestSellers) && $bestSellers->count() > 0)
                    <a href="{{ route('products.index', ['sort' => 'best_selling']) }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition group">
                        View All
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>
            @if(isset($bestSellers) && $bestSellers->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($bestSellers as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @else
                <x-empty-state
                    icon="sparkles"
                    title="Best sellers coming soon"
                    message="Popular picks will appear here once orders start rolling in."
                    :action="route('products.index')"
                    actionText="Shop Collection"
                />
            @endif
        </div>
    </section>

    {{-- Trending Products --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 scroll-fade-in">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="inline-block px-3 py-1 bg-purple-50 text-purple-600 text-xs font-semibold rounded-full mb-3">Trending</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-serif">Trending Now</h2>
                <p class="text-gray-400 text-sm mt-1">What everyone's talking about</p>
            </div>
            @if($trendingProducts->count() > 0)
                <a href="{{ route('products.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition group">
                    View All
                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>
        @if($trendingProducts->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($trendingProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <x-empty-state
                icon="sparkles"
                title="Nothing trending right now"
                message="Trending items will show up here as more people shop."
                :action="route('products.index')"
                actionText="Browse Products"
            />
        @endif
    </section>

    {{-- Brands --}}
    @if($brands->count() > 0)
        <section class="bg-white border-t border-gray-100 py-12 sm:py-16 scroll-fade-in">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full mb-3">Brands</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 font-serif">Shop by Brand</h2>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-6 sm:gap-10 lg:gap-16">
                    @foreach($brands as $brand)
                        <a href="{{ route('products.index', ['brand' => $brand]) }}"
                           class="group px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                            <span class="text-base sm:text-lg font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $brand }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Newsletter --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700 py-16 sm:py-20 scroll-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white font-serif mb-3">Join Our Newsletter</h2>
                <p class="text-indigo-200 text-sm sm:text-base mb-8 leading-relaxed">Subscribe to get special offers, free giveaways, and early access to new arrivals.</p>
                <div x-data="{
                    email: '',
                    error: '',
                    submitting: false,
                    submit() {
                        if (this.submitting) return;
                        if (!this.email.trim()) { this.error = 'Please enter your email address.'; return; }
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim())) { this.error = 'Please enter a valid email address.'; return; }
                        this.error = '';
                        this.submitting = true;
                        window.setTimeout(() => {
                            this.submitting = false;
                            this.email = '';
                            window.Fashion.success('Thanks for subscribing! Check your inbox for a confirmation.');
                        }, 600);
                    }
                }">
                    <form method="POST" action="#" @submit.prevent="submit" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto" novalidate>
                        <input type="email" name="email" x-model="email" placeholder="Enter your email address"
                               :class="error ? 'ring-2 ring-red-400' : 'focus:ring-2 focus:ring-white/30'"
                               class="flex-1 px-5 py-3.5 rounded-xl border-0 text-sm text-gray-900 placeholder-gray-400 outline-none shadow-sm transition">
                        <button type="submit" :disabled="submitting"
                                :class="submitting ? 'opacity-70 cursor-wait' : 'hover:bg-indigo-50'"
                                class="px-8 py-3.5 bg-white text-indigo-700 font-semibold rounded-xl transition-all shadow-sm text-sm whitespace-nowrap">
                            <span x-show="!submitting">Subscribe</span>
                            <span x-show="submitting" class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Subscribing…
                            </span>
                        </button>
                    </form>
                    <p x-show="error" x-cloak x-text="error" class="text-red-200 text-sm mt-3"></p>
                    <p class="text-indigo-300/70 text-xs mt-4">No spam. Unsubscribe anytime.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
