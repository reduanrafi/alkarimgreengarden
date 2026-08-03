<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = CatalogService::categories();

        $products = $this->buildProductQuery($request)->paginate(12)->withQueryString();

        $brands = CatalogService::brands();
        $fabrics = CatalogService::fabrics();
        $colors = CatalogService::colors();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('products.partials.catalog', compact('products'))->render(),
                'total' => $products->total(),
            ]);
        }

        return view('products.index', compact('categories', 'products', 'brands', 'fabrics', 'colors'));
    }

    public function category(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $categories = CatalogService::categories();

        $products = $this->buildProductQuery($request, $category)->paginate(12)->withQueryString();

        $brands = CatalogService::brands();
        $fabrics = CatalogService::fabrics();
        $colors = CatalogService::colors();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('products.partials.catalog', compact('products'))->render(),
                'total' => $products->total(),
            ]);
        }

        return view('products.index', compact('categories', 'products', 'brands', 'fabrics', 'colors', 'category'));
    }

    private function buildProductQuery(Request $request, ?Category $category = null): Builder
    {
        $sort = $request->get('sort', 'latest');

        $query = Product::with('category')
            ->active()
            ->withCount(['reviews' => fn ($q) => $q->where('status', true)]);

        if ($sort !== 'popular') {
            $query->withAvg(['reviews' => fn ($q) => $q->where('status', true)], 'rating');
        }

        if (auth()->check()) {
            $query->with(['wishlists' => fn ($q) => $q->where('user_id', auth()->id())]);
        }

        if ($category) {
            $query->where('category_id', $category->id);
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('fabric', 'LIKE', "%{$search}%")
                    ->orWhere('color', 'LIKE', "%{$search}%")
                    ->orWhere('brand', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', fn ($cq) => $cq->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if (! $category && $categorySlug = $request->get('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($brand = $request->get('brand')) {
            $query->where('brand', $brand);
        }

        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($fabric = $request->get('fabric')) {
            $query->where('fabric', $fabric);
        }

        if ($color = $request->get('color')) {
            $query->where('color', $color);
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('discounted')) {
            $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
        }

        $query->matchSort($sort);

        return $query;
    }

    public function show($slug)
    {
        $product = Product::with([
                'category',
                'productAttributeValues.attribute',
                'productAttributeValues.attributeValue',
                'reviews' => fn ($q) => $q->where('status', true)->with('user')->latest(),
            ])
            ->withCount(['reviews' => fn ($q) => $q->where('status', true)])
            ->withAvg(['reviews' => fn ($q) => $q->where('status', true)], 'rating')
            ->when(auth()->check(), fn ($q) => $q->with(['wishlists' => fn ($q) => $q->where('user_id', auth()->id())]))
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $related = Product::with('category')
            ->withCount(['reviews' => fn ($q) => $q->where('status', true)])
            ->withAvg(['reviews' => fn ($q) => $q->where('status', true)], 'rating')
            ->when(auth()->check(), fn ($q) => $q->with(['wishlists' => fn ($q) => $q->where('user_id', auth()->id())]))
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
