<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;

class HomeController extends Controller
{
    public function index()
    {
        $categories = CatalogService::categories();
        $featuredProducts = CatalogService::featuredProducts();
        $latestProducts = CatalogService::latestProducts();
        $bestSellers = CatalogService::bestSellers();
        $heroBanners = CatalogService::heroBanners();
        $banner = $heroBanners->first();
        $carouselBanners = CatalogService::carouselBanners();
        $fixedBanner = CatalogService::fixedBanner();
        $especiallyForYou = CatalogService::especiallyForYou();

        return view('home.index', compact('categories', 'featuredProducts', 'latestProducts', 'bestSellers', 'heroBanners', 'banner', 'carouselBanners', 'fixedBanner', 'especiallyForYou'));
    }
}
