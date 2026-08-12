<?php

use App\Models\Category;
use App\Models\Faq;
use App\Models\PlantCareGuide;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    $products = Product::active()->orderBy('id')->cursor();
    $categories = Category::active()->orderBy('id')->cursor();
    $careGuides = PlantCareGuide::active()->orderBy('id')->cursor();
    $faqs = Faq::active()->orderBy('id')->cursor();

    return response()->view('sitemap', [
        'products' => $products,
        'categories' => $categories,
        'careGuides' => $careGuides,
        'faqs' => $faqs,
    ])->header('Content-Type', 'application/xml');
});
