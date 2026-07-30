<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $coupon = Coupon::where('code', strtoupper($validated['code']))->first();

        if (! $coupon || ! $coupon->isValid()) {
            $msg = 'Invalid or expired coupon code.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 400)
                : back()->with('error', $msg);
        }

        $cart = app(CartService::class);
        $subtotal = $cart->getTotal();

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            $msg = 'Minimum order amount of ' . formatPrice($coupon->min_order_amount) . ' required.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 400)
                : back()->with('error', $msg);
        }

        $discount = $coupon->apply($subtotal);
        session(['coupon' => ['code' => $coupon->code, 'discount' => $discount]]);

        $msg = 'Coupon applied! You saved ' . formatPrice($discount);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'discount' => $discount,
                'code' => $coupon->code,
                'count' => $cart->getCount(),
                'subtotal' => $subtotal,
                'shipping_charge' => $subtotal >= 100 ? 0 : 9.99,
                'grand_total' => max(0, $subtotal + ($subtotal >= 100 ? 0 : 9.99) - $discount),
            ]);
        }

        return back()->with('success', $msg);
    }

    public function remove()
    {
        session()->forget('coupon');

        if (request()->ajax()) {
            $cart = app(CartService::class);
            $items = $cart->getCart();
            $subtotal = array_sum(array_map(fn ($i) => ($i['final_price'] ?? $i['price']) * $i['quantity'], $items));
            $shippingCharge = $subtotal >= 100 ? 0 : 9.99;

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed.',
                'discount' => 0,
                'count' => $cart->getCount(),
                'subtotal' => $subtotal,
                'shipping_charge' => $shippingCharge,
                'grand_total' => max(0, $subtotal + $shippingCharge),
            ]);
        }

        return back()->with('success', 'Coupon removed.');
    }
}
