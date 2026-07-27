<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $heroBanner = Banner::hero()->active()->first();
        $carouselBanners = Banner::carousel()->active()->orderBy('display_order')->get();
        $fixedBanners = Banner::fixed()->active()->get();

        return response()->json([
            'hero' => $heroBanner,
            'carousel' => $carouselBanners,
            'fixed' => $fixedBanners,
        ]);
    }
}
