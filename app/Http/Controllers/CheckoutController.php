<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->middleware('auth');
        $this->cart = $cart;
    }

    protected function calculateCart(string $shippingMethod = 'standard')
    {
        $raw = $this->cart->getRawCart();
        $cartItems = $this->cart->getCart();
        $subtotal = array_sum(array_map(fn ($i) => ($i['final_price'] ?? $i['price']) * $i['quantity'], $cartItems));

        $shippingRates = [
            'standard' => 9.99,
            'express' => 19.99,
            'store_pickup' => 0,
        ];
        $shippingCharge = $subtotal >= 100 ? 0 : ($shippingRates[$shippingMethod] ?? 9.99);
        $discount = session('coupon.discount', 0);
        $tax = round($subtotal * 0.05, 2);
        $grandTotal = max(0, $subtotal + $shippingCharge + $tax - $discount);

        return compact('cartItems', 'raw', 'subtotal', 'shippingCharge', 'discount', 'tax', 'grandTotal');
    }

    public function create()
    {
        $data = $this->calculateCart();

        if (empty($data['cartItems'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data['count'] = $this->cart->getCount();

        return view('checkout.index', $data);
    }

    public function store(StoreOrderRequest $request)
    {
        // Idempotency guard FIRST: if this checkout token already placed an order,
        // return that order instead of creating a duplicate (double-submit or retry
        // after a lost response) — even when the cart has already been cleared.
        $checkoutToken = (string) $request->input('checkout_token', '');

        if ($checkoutToken !== '' && ($existingOrderId = session()->get('checkout.token.' . $checkoutToken))) {
            if ($existingOrder = Order::find($existingOrderId)) {
                return $request->ajax()
                    ? response()->json([
                        'success' => true,
                        'message' => 'Order already placed.',
                        'redirect' => route('orders.success', $existingOrder),
                    ])
                    : redirect()->route('orders.success', $existingOrder)
                        ->with('success', 'Order placed successfully!');
            }
        }

        $data = $this->calculateCart($request->shipping_method ?? 'standard');

        if (empty($data['cartItems'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.',
                    'redirect' => route('cart.index'),
                ], 422);
            }

            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $data['subtotal'];
        $shippingCharge = $data['shippingCharge'];
        $discount = $data['discount'];
        $tax = $data['tax'];
        $grandTotal = $data['grandTotal'];
        $couponCode = session('coupon.code');
        $paymentMethod = $request->payment_method;

        DB::beginTransaction();

        try {
            $notes = $request->notes;
            if ($request->shipping_method) {
                $shippingLabels = ['standard' => 'Standard Delivery', 'express' => 'Express Delivery', 'store_pickup' => 'Store Pickup'];
                $notes = ($notes ? $notes . "\n\n" : '') . 'Shipping Method: ' . ($shippingLabels[$request->shipping_method] ?? $request->shipping_method);
            }

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
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_charge' => $shippingCharge,
                'discount' => $discount,
                'coupon_code' => $couponCode,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'total' => $grandTotal,
                'status' => 'pending',
                'notes' => $notes,
                'ordered_at' => now(),
            ]);

            foreach ($data['raw'] as $id => $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($id);

                if (! $product->status || $product->stock < $item['quantity']) {
                    throw new \Exception("{$product->name} is no longer available in the requested quantity.");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('used_count');
            }

            if ($request->boolean('save_address') && auth()->check()) {
                auth()->user()->update(array_filter([
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city ?: null,
                    'postal_code' => $request->postal_code ?: null,
                ]));
            }

            DB::commit();
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::error('Checkout failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            session()->flash('error', $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'redirect' => route('orders.failed'),
                ], 422);
            }

            return redirect()->route('orders.failed')
                ->with('error', $e->getMessage());
        }

        // ------------------------------------------------------------------
        // The order is committed at this point. Always return success — the
        // confirmation email and cart cleanup must never delay or fail the
        // response (order must not be duplicated by a retry).
        // ------------------------------------------------------------------

        if ($checkoutToken !== '') {
            session(['checkout.token.' . $checkoutToken => $order->id]);
        }

        try {
            $this->cart->clear();
            session()->forget('coupon');
        } catch (\Throwable $e) {
            Log::error('Checkout cleanup failed: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            $response = response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect' => route('orders.success', $order),
            ]);
        } else {
            $response = redirect()->route('orders.success', $order)
                ->with('success', 'Order placed successfully!');
        }

        // Send the confirmation email after the response has been sent to the
        // client, so a slow/unreachable mail server can never leave the user
        // stuck on "Processing..." — the response always returns immediately.
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

        return $response;
    }
}
