<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;

class HomeController extends Controller
{
    public function index()
    {
        $categories = CatalogService::categories();
        $topCategories = CatalogService::topCategories();
        $featuredProducts = CatalogService::featuredProducts();
        $latestProducts = CatalogService::latestProducts();
        $bestSellers = CatalogService::bestSellers();
        $categorySections = CatalogService::categorySections();
        $heroBanners = CatalogService::heroBanners();
        $banner = $heroBanners->first();
        $carouselBanners = CatalogService::carouselBanners();
        $fixedBanner = CatalogService::fixedBanner();
        $especiallyForYou = CatalogService::especiallyForYou();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $latestProducts;
        }

        return view('home.index', compact('categories', 'topCategories', 'featuredProducts', 'latestProducts', 'bestSellers', 'categorySections', 'heroBanners', 'banner', 'carouselBanners', 'fixedBanner', 'especiallyForYou'));
    }
}
