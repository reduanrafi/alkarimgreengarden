<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
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

        $sort = $request->get('sort', 'latest');
        $query->matchSort($sort);

        $products = $query->paginate(12);

        return response()->json($products);
    }

    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with('category')->active()
            ->where(function ($qb) use ($query) {
                $qb->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('fabric', 'LIKE', "%{$query}%")
                    ->orWhere('color', 'LIKE', "%{$query}%");
            })
            ->limit(8)
            ->get(['id', 'name', 'slug', 'price', 'image']);

        return response()->json($products->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'url' => route('products.show', $p->slug),
            'price' => formatPrice($p->price),
            'image' => $p->image ? '<img src="' . asset('storage/' . $p->image) . '" class="w-full h-full object-cover rounded-lg">' : null,
            'category' => $p->category?->name,
        ]));
    }
}
