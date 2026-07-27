<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::where('status', true)
            ->withCount('products')
            ->get();

        return response()->json($categories);
    }

    public function show($slug, Request $request): JsonResponse
    {
        $category = Category::where('slug', $slug)->where('status', true)->firstOrFail();

        $query = Product::with('category')
            ->where('category_id', $category->id)
            ->where('status', true);

        $sort = $request->get('sort', 'latest');
        $query->matchSort($sort);

        $perPage = min((int) $request->get('per_page', 12), 48);
        $products = $query->paginate($perPage);

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}
