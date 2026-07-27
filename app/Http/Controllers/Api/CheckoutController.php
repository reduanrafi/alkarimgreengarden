<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Mail\OrderPlaced;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->middleware('auth:sanctum');
        $this->cart = $cart;
    }

    public function store(CheckoutRequest $request): JsonResponse
    {
        $cartItems = $this->cart->getCart();

        if (empty($cartItems)) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        $subtotal = $this->cart->getTotal();
        $shippingCharge = $subtotal >= 100 ? 0 : 9.99;
        $discount = session('coupon.discount', 0);
        $grandTotal = max(0, $subtotal + $shippingCharge - $discount);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'division' => $request->division,
                'district' => $request->district,
                'upazila' => $request->upazila,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_charge' => $shippingCharge,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'total' => $grandTotal,
                'status' => 'pending',
                'notes' => $request->notes,
                'ordered_at' => now(),
            ]);

            foreach ($cartItems as $id => $item) {
                $product = Product::findOrFail($id);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            if ($couponCode = session('coupon.code')) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }

            DB::commit();

            try {
                Mail::to($order->email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
            }

            $this->cart->clear();
            session()->forget('coupon');

            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order->load('items.product'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
