<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Mail\OrderPlaced;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $data = $this->calculateCart($request->shipping_method ?? 'standard');

        if (empty($data['cartItems'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $data['subtotal'];
        $shippingCharge = $data['shippingCharge'];
        $discount = $data['discount'];
        $tax = $data['tax'];
        $grandTotal = $data['grandTotal'];

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
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_charge' => $shippingCharge,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'total' => $grandTotal,
                'status' => 'pending',
                'notes' => $notes,
                'ordered_at' => now(),
            ]);

            foreach ($data['raw'] as $id => $item) {
                $product = Product::findOrFail($id);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'price' => $item['price'],
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
                // Silently fail if email cannot be sent
            }

            $this->cart->clear();
            session()->forget('coupon');

            return redirect()->route('orders.success', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->route('checkout.create')
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
}
