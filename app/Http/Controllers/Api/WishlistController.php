<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ApiCartService;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function __construct(protected ApiCartService $cart)
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
        $this->cart->add($product, 1);
        auth()->user()->wishlists()->where('product_id', $product->id)->delete();

        return response()->json(['message' => 'Product moved to cart.']);
    }
}
