@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">About Us</h1>
        <p class="text-gray-500 mt-2 max-w-2xl mx-auto">Discover our story, mission, and what drives us forward.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
        <div>
            <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center text-6xl">👗</div>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Story</h2>
            <p class="text-gray-600 leading-relaxed mb-4">Founded with a passion for quality fashion, we set out to create a brand that combines style, comfort, and affordability. What started as a small collection has grown into a curated marketplace offering everything from casual wear to premium accessories.</p>
            <p class="text-gray-600 leading-relaxed">Every piece in our collection is thoughtfully selected to ensure our customers look and feel their best. We believe fashion is a form of self-expression, and we're here to help you tell your story.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="text-4xl mb-4">🎯</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Our Mission</h3>
            <p class="text-gray-500 text-sm leading-relaxed">To provide high-quality, fashionable clothing that empowers individuals to express their unique style without breaking the bank.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="text-4xl mb-4">👁️</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Our Vision</h3>
            <p class="text-gray-500 text-sm leading-relaxed">To become the most trusted and loved fashion destination, known for exceptional quality, outstanding service, and a seamless shopping experience.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="text-4xl mb-4">🤝</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Our Values</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Quality, integrity, customer satisfaction, and sustainability guide everything we do. We believe in doing business the right way.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Why Shop With Us?</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
            <div><div class="text-3xl mb-2">🚚</div><p class="font-semibold text-gray-900">Fast Shipping</p><p class="text-sm text-gray-500">Free delivery on orders over ৳100</p></div>
            <div><div class="text-3xl mb-2">🛡️</div><p class="font-semibold text-gray-900">Secure Payments</p><p class="text-sm text-gray-500">Safe and encrypted transactions</p></div>
            <div><div class="text-3xl mb-2">↩️</div><p class="font-semibold text-gray-900">Easy Returns</p><p class="text-sm text-gray-500">30-day return policy</p></div>
            <div><div class="text-3xl mb-2">💬</div><p class="font-semibold text-gray-900">24/7 Support</p><p class="text-sm text-gray-500">We're here to help anytime</p></div>
        </div>
    </div>
</div>
@endsection