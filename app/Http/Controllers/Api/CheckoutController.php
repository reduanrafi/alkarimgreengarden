<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\ApiCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cart;

    public function __construct(ApiCartService $cart)
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
        $discount = $this->cart->getCoupon()['discount'] ?? 0;
        $tax = round($subtotal * 0.05, 2);
        $grandTotal = max(0, $subtotal + $shippingCharge + $tax - $discount);

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
                'tax' => $tax,
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

            if ($couponCode = $this->cart->getCoupon()['code'] ?? null) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }

            DB::commit();

            if (filled($order->email)) {
                try {
                    SendOrderConfirmationEmail::dispatch($order->id);
                } catch (\Throwable $e) {
                    Log::warning('Order confirmation email could not be queued.', [
                        'order_id' => $order->id,
                        'exception' => $e->getMessage(),
                    ]);
                }
            }

            $this->cart->clear();
            $this->cart->removeCoupon();

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
