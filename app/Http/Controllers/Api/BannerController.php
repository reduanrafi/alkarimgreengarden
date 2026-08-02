<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $heroBanner = CatalogService::heroBanners()->first();
        $carouselBanners = CatalogService::carouselBanners();
        $fixedBanners = CatalogService::fixedBanner()
            ? collect([CatalogService::fixedBanner()])
            : collect();

        return response()->json([
            'hero' => $heroBanner,
            'carousel' => $carouselBanners,
            'fixed' => $fixedBanners,
        ]);
    }
}
