<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)->get();
        $featuredProducts = Product::with('category')->active()->featured()->latest()->limit(8)->get();
        $latestProducts = Product::with('category')->active()->latest()->limit(8)->get();
        $bestSellers = Product::with('category')->active()
            ->withCount('orderItems')
            ->having('order_items_count', '>', 0)
            ->orderByDesc('order_items_count')
            ->limit(8)
            ->get();
        $banner = Banner::hero()->active()->first();
        $carouselBanners = Banner::carousel()->active()->orderBy('display_order')->get();
        $fixedBanner = Banner::fixed()->active()->first();
        $especiallyForYou = Banner::especiallyForYou()->active()->orderBy('display_order')->get();

        return view('home.index', compact('categories', 'featuredProducts', 'latestProducts', 'bestSellers', 'banner', 'carouselBanners', 'fixedBanner', 'especiallyForYou'));
    }
}
