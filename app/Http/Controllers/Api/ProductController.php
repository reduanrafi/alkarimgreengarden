<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->active();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('fabric', 'LIKE', "%{$search}%")
                    ->orWhere('color', 'LIKE', "%{$search}%")
                    ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        if ($categorySlug = $request->get('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
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

        $sort = $request->get('sort', 'latest');
        $query->matchSort($sort);

        $perPage = min((int) $request->get('per_page', 12), 48);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function recent(Request $request): JsonResponse
    {
        $ids = collect(explode(',', $request->get('ids', '')))
            ->filter(fn($id) => is_numeric($id))
            ->values();

        if ($ids->isEmpty()) {
            return response()->json([]);
        }

        $products = Product::active()
            ->whereIn('id', $ids)
            ->get(['id', 'name', 'slug', 'price', 'image']);

        $sorted = $ids->map(fn($id) => $products->firstWhere('id', (int) $id))->filter();

        return response()->json($sorted->values());
    }

    public function show($slug): JsonResponse
    {
        $product = Product::with([
            'category',
            'productAttributeValues.attribute',
            'productAttributeValues.attributeValue',
            'reviews' => fn($q) => $q->where('status', true)->with('user')->latest(),
        ])->where('slug', $slug)->where('status', true)->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related' => $related,
            'reviews_count' => $product->reviews_count,
            'avg_rating' => $product->avg_rating,
        ]);
    }
}
