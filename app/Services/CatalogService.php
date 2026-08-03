<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;

class CatalogService
{
    private const TTL = 3600;

    private const HOME_TTL = 300;

    private const CATEGORIES_KEY = 'catalog.categories';

    private const HOME_KEYS = [
        'featured' => 'catalog.home.featured',
        'latest' => 'catalog.home.latest',
        'best_sellers' => 'catalog.home.best_sellers',
        'category_sections' => 'catalog.home.category_sections',
        'top_categories' => 'catalog.home.top_categories',
    ];

    public static function categories(): Collection
    {
        return Cache::remember(self::CATEGORIES_KEY, self::TTL, function () {
            return Category::active()->orderBy('name')->get();
        });
    }

    public static function topCategories(int $limit = 6): Collection
    {
        return Cache::remember(self::HOME_KEYS['top_categories'], self::HOME_TTL, function () use ($limit) {
            return Category::active()
                ->withCount(['products' => fn ($q) => $q->where('status', true)])
                ->orderByDesc('products_count')
                ->orderBy('name')
                ->limit($limit)
                ->get();
        });
    }

    public static function heroBanners(): Collection
    {
        return Cache::remember('catalog.banners.hero', self::TTL, function () {
            return Banner::hero()->active()->orderBy('display_order')->get();
        });
    }

    public static function carouselBanners(): Collection
    {
        return Cache::remember('catalog.banners.carousel', self::TTL, function () {
            return Banner::carousel()->active()->orderBy('display_order')->get();
        });
    }

    public static function fixedBanner(): ?Banner
    {
        return Cache::remember('catalog.banners.fixed', self::TTL, function () {
            return Banner::fixed()->active()->first();
        });
    }

    public static function especiallyForYou(): Collection
    {
        return Cache::remember('catalog.banners.especially', self::TTL, function () {
            return Banner::especiallyForYou()->active()->orderBy('display_order')->get();
        });
    }

    public static function brands(): SupportCollection
    {
        return Cache::remember('catalog.facets.brands', self::TTL, function () {
            return Product::active()->whereNotNull('brand')->distinct()->pluck('brand')->sort();
        });
    }

    public static function fabrics(): SupportCollection
    {
        return Cache::remember('catalog.facets.fabrics', self::TTL, function () {
            return Product::active()->whereNotNull('fabric')->distinct()->pluck('fabric')->sort();
        });
    }

    public static function colors(): SupportCollection
    {
        return Cache::remember('catalog.facets.colors', self::TTL, function () {
            return Product::active()->whereNotNull('color')->distinct()->pluck('color')->sort();
        });
    }

    public static function featuredProducts(): Collection
    {
        return Cache::remember(self::HOME_KEYS['featured'], self::HOME_TTL, function () {
            return self::withUserContext(self::withRatingAggregates(Product::query()))
                ->active()
                ->featured()
                ->latest()
                ->limit(8)
                ->get();
        });
    }

    public static function latestProducts(): Collection
    {
        return Cache::remember(self::HOME_KEYS['latest'], self::HOME_TTL, function () {
            return self::withUserContext(self::withRatingAggregates(Product::query()))
                ->active()
                ->latest()
                ->limit(8)
                ->get();
        });
    }

    public static function bestSellers(): Collection
    {
        return Cache::remember(self::HOME_KEYS['best_sellers'], self::HOME_TTL, function () {
            $query = self::withUserContext(self::withRatingAggregates(Product::query()))
                ->active();

            $bestSellers = (clone $query)
                ->withCount('orderItems')
                ->having('order_items_count', '>', 0)
                ->orderByDesc('order_items_count')
                ->limit(8)
                ->get();

            if ($bestSellers->isNotEmpty()) {
                return $bestSellers;
            }

            return $query->orderByDesc('reviews_avg_rating')
                ->orderByDesc('id')
                ->limit(8)
                ->get();
        });
    }

    public static function categorySections(): SupportCollection
    {
        return Cache::remember(self::HOME_KEYS['category_sections'], self::HOME_TTL, function () {
            $categories = Category::active()->orderBy('name')->get();

            return $categories
                ->filter(function (Category $category) {
                    $products = self::withUserContext(self::withRatingAggregates($category->products()->getQuery()))
                        ->active()
                        ->latest()
                        ->limit(8)
                        ->get();

                    $category->setRelation('products', $products);

                    return $products->isNotEmpty();
                })
                ->values();
        });
    }

    private static function withRatingAggregates($query)
    {
        return $query->with('category')
            ->withCount(['reviews' => fn ($q) => $q->where('status', true)])
            ->withAvg(['reviews' => fn ($q) => $q->where('status', true)], 'rating');
    }

    private static function withUserContext($query)
    {
        return $query->when(auth()->check(), fn ($q) => $q->with([
            'wishlists' => fn ($w) => $w->where('user_id', auth()->id()),
        ]));
    }

    public static function flushCategories(): void
    {
        Cache::forget(self::CATEGORIES_KEY);
    }

    public static function flushBanners(): void
    {
        foreach (['hero', 'carousel', 'fixed', 'especially'] as $type) {
            Cache::forget('catalog.banners.' . $type);
        }
    }

    public static function flushFacets(): void
    {
        foreach (['brands', 'fabrics', 'colors'] as $facet) {
            Cache::forget('catalog.facets.' . $facet);
        }
    }

    public static function flushHomeProducts(): void
    {
        foreach (self::HOME_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
