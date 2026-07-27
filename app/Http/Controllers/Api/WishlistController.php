<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
        $wishlists = auth()->user()->wishlists()->with('product.category')->latest()->paginate(12);

        return response()->json($wishlists);
    }

    public function toggle(Product $product): JsonResponse
    {
        $user = auth()->user();
        $existing = $user->wishlists()->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['in_wishlist' => false, 'message' => 'Removed from wishlist.']);
        }

        $user->wishlists()->create(['product_id' => $product->id]);
        return response()->json(['in_wishlist' => true, 'message' => 'Added to wishlist.']);
    }

    public function destroy(Product $product): JsonResponse
    {
        auth()->user()->wishlists()->where('product_id', $product->id)->delete();

        return response()->json(['message' => 'Removed from wishlist.']);
    }

    public function moveToCart(Product $product): JsonResponse
    {
        $cart = session('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = min($cart[$id]['quantity'] + 1, $product->stock);
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'category_slug' => $product->category->slug ?? '',
            ];
        }

        session(['cart' => $cart]);
        auth()->user()->wishlists()->where('product_id', $id)->delete();

        return response()->json(['message' => 'Product moved to cart.']);
    }
}
