<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    protected function cartResponse()
    {
        $items = $this->cart->getCart();
        $subtotal = $this->cart->getSubtotal();
        $count = $this->cart->getCount();
        $shippingCharge = $subtotal >= 100 ? 0 : 9.99;
        $discount = session('coupon.discount', 0);
        $tax = round($subtotal * 0.05, 2);

        return [
            'items' => array_values($items),
            'count' => $count,
            'subtotal' => $subtotal,
            'shipping_charge' => $shippingCharge,
            'discount' => $discount,
            'tax' => $tax,
            'grand_total' => max(0, $subtotal + $shippingCharge + $tax - $discount),
        ];
    }

    public function index()
    {
        $data = $this->cartResponse();

        return view('cart.index', array_merge($data, [
            'shippingCharge' => $data['shipping_charge'],
            'grandTotal' => $data['grand_total'],
            'tax' => $data['tax'],
        ]));
    }

    public function items()
    {
        return response()->json($this->cartResponse());
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('category')->findOrFail($id);

        if (! $product->status) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'This product is unavailable.'])
                : back()->with('error', 'This product is unavailable.');
        }

        $quantity = min($request->quantity, $product->stock);
        $this->cart->add($product, $quantity);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'count' => $this->cart->getCount(),
            ] + $this->cartResponse());
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $quantity = min($request->quantity, $product->stock);
        $this->cart->update($id, $quantity);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated!',
            ] + $this->cartResponse());
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $this->cart->remove($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
            ] + $this->cartResponse());
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cart->clear();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared.',
                'count' => 0,
                'subtotal' => 0,
                'shipping_charge' => 0,
                'discount' => 0,
                'grand_total' => 0,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
