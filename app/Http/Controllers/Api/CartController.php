<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ApiCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;

    public function __construct(ApiCartService $cart)
    {
        $this->cart = $cart;
    }

    public function index(): JsonResponse
    {
        $cartItems = $this->cart->getCart();
        $total = $this->cart->getTotal();
        $count = $this->cart->getCount();
        $shippingCharge = $total >= 100 ? 0 : 9.99;
        $discount = $this->cart->getCoupon()['discount'] ?? 0;
        $tax = round($total * 0.05, 2);

        return response()->json([
            'items' => $cartItems,
            'total' => $total,
            'count' => $count,
            'subtotal' => $total,
            'shipping_charge' => $shippingCharge,
            'tax' => $tax,
            'grand_total' => max(0, $total + $shippingCharge + $tax - $discount),
            'discount' => $discount,
        ]);
    }

    public function add(Request $request, $id): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = Product::with('category')->findOrFail($id);

        if (!$product->status) {
            return response()->json(['success' => false, 'message' => 'This product is unavailable.'], 400);
        }

        $quantity = min($request->quantity, $product->stock);
        $this->cart->add($product, $quantity);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'count' => $this->cart->getCount(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = Product::findOrFail($id);
        $quantity = min($request->quantity, $product->stock);
        $this->cart->update($id, $quantity);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated!',
            'count' => $this->cart->getCount(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    public function remove($id): JsonResponse
    {
        $this->cart->remove($id);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'count' => $this->cart->getCount(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cart->clear();

        return response()->json(['message' => 'Cart cleared.']);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        $coupon = \App\Models\Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon code.'], 400);
        }

        $subtotal = $this->cart->getTotal();
        $discount = $coupon->apply($subtotal);

        $this->cart->setCoupon(['code' => $coupon->code, 'discount' => $discount]);

        $shippingCharge = $subtotal >= 100 ? 0 : 9.99;
        $tax = round($subtotal * 0.05, 2);

        return response()->json([
            'message' => 'Coupon applied!',
            'discount' => $discount,
            'grand_total' => max(0, $subtotal + $shippingCharge + $tax - $discount),
        ]);
    }

    public function removeCoupon(): JsonResponse
    {
        $this->cart->removeCoupon();

        return response()->json(['message' => 'Coupon removed.']);
    }
}
