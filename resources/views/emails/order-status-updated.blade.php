<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>body{font-family:system-ui,sans-serif;max-width:600px;margin:0 auto;padding:20px;color:#333}.btn{display:inline-block;padding:10px 20px;background:#1f5c3f;color:#fff;text-decoration:none;border-radius:8px;font-size:14px}</style></head>
<body>
    <h1 style="color:#173d2b">Order Status Updated</h1>
    <p>Hi <strong>{{ $order->customer_name }}</strong>,</p>
    <p>Your order <strong>#{{ $order->id }}</strong> status has been updated.</p>
    <p><strong>Current Status:</strong> <span style="color:#3f8a5c;font-weight:bold">{{ ucfirst($order->status) }}</span></p>
    <p><strong>Payment Status:</strong> <span style="color:#3f8a5c;font-weight:bold">{{ ucfirst($order->payment_status) }}</span></p>
    <a href="{{ route('orders.show', $order) }}" class="btn">Track Order</a>
    <p style="color:#999;font-size:12px;margin-top:20px">Thank you for shopping with Green Garden!</p>
</body></html>