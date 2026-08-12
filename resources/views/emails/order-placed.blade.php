<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>body{font-family:system-ui,sans-serif;max-width:600px;margin:0 auto;padding:20px;color:#333}table{width:100%;border-collapse:collapse}th,td{padding:8px 12px;text-align:left;border-bottom:1px solid #eee}.btn{display:inline-block;padding:10px 20px;background:#1f5c3f;color:#fff;text-decoration:none;border-radius:8px;font-size:14px}</style></head>
<body>
    <h1 style="color:#173d2b">Order Confirmed!</h1>
    <p>Hi <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Your order <strong>#{{ $order->id }}</strong> has been placed successfully. Here's a summary:</p>
    <table><tr><th>Product</th><th>Qty</th><th>Total</th></tr>
    @foreach($order->items as $item)
        <tr><td>{{ $item->product->name ?? 'N/A' }}</td><td>{{ $item->quantity }}</td><td>{{ formatPrice($item->price * $item->quantity) }}</td></tr>
    @endforeach
    </table>
    <p><strong>Total:</strong> {{ formatPrice($order->grand_total) }}</p>
    <p><strong>Shipping to:</strong> {{ $order->address }}, {{ $order->district }}, {{ $order->division }}</p>
    <a href="{{ route('orders.show', $order) }}" class="btn">View Order</a>
    <p style="color:#999;font-size:12px;margin-top:20px">Thank you for shopping with Green Garden!</p>
</body></html>