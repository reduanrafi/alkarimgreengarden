<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice #{{ $order->invoice_no ?? $order->id }} - {{ config('app.name') }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: system-ui, -apple-system, sans-serif; background: #f7f9f6; color: #22281f; padding: 40px; max-width: 900px; margin: auto; }
    .invoice { border: 1px solid #e6e9e2; border-radius: 16px; padding: 40px; background: #ffffff; }
    .top { display: flex; justify-content: space-between; align-items: start; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #3f8a5c; }
    .store-name { font-size: 24px; font-weight: 800; color: #173d2b; letter-spacing: -0.5px; }
    .store-name span { color: #8a938a; font-weight: 400; font-size: 12px; display: block; margin-top: 2px; }
    .invoice-title { font-size: 32px; font-weight: 800; color: #173d2b; letter-spacing: 2px; margin-top: 6px; }
    .badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; background: #e4efe4; color: #1f5c3f; }
    .meta { color: #5b6259; font-size: 13px; line-height: 1.8; }
    .meta strong { color: #173d2b; }
    .section { margin-bottom: 24px; }
    .section-title { font-size: 11px; font-weight: 600; color: #8a938a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; margin: 16px 0; }
    th { text-align: left; padding: 10px 8px; color: #5b6259; border-bottom: 1px solid #e6e9e2; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    td { padding: 10px 8px; border-bottom: 1px solid #e6e9e2; }
    .totals { width: 320px; margin-left: auto; margin-top: 12px; }
    .totals td { padding: 5px 8px; border: none; }
    .totals tr:last-child td { padding-top: 10px; border-top: 2px solid #3f8a5c; font-size: 18px; font-weight: 700; color: #173d2b; }
    .footer { text-align: center; margin-top: 32px; padding-top: 20px; border-top: 1px solid #e6e9e2; color: #8a938a; font-size: 11px; }
</style>
</head>
<body>
<div class="invoice">
    <div class="top">
        <div>
            <div class="store-name">🌿 {{ config('app.name') }}<span>Green Garden</span></div>
            <div class="invoice-title">INVOICE</div>
        </div>
        <div style="text-align:right;">
            <span class="badge">{{ strtoupper($order->status) }}</span>
            <div class="meta" style="margin-top: 10px;">
                <strong>Invoice #:</strong> {{ $order->invoice_no ?? 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}<br>
                <strong>Date:</strong> {{ $order->ordered_at?->format('M d, Y') ?? $order->created_at->format('M d, Y') }}<br>
                <strong>Order ID:</strong> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </div>

    <div class="grid-2 section">
        <div>
            <div class="section-title">Billed To</div>
            <div class="meta">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->email }}<br>
                {{ $order->phone }}
            </div>
        </div>
        <div>
            <div class="section-title">Ship To</div>
            <div class="meta">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->address }}<br>
                {{ collect([$order->upazila, $order->district])->filter()->implode(', ') }}<br>
                {{ $order->division }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Items</div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Price</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Product' }}</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">{{ formatPrice($item->price) }}</td>
                        <td style="text-align:right;">{{ formatPrice($item->price * $item->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td style="text-align:right;">{{ formatPrice($order->subtotal) }}</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td style="text-align:right;">{{ $order->shipping_charge > 0 ? formatPrice($order->shipping_charge) : 'Free' }}</td>
            </tr>
            @if($order->tax > 0)
                <tr>
                    <td>Tax</td>
                    <td style="text-align:right;">{{ formatPrice($order->tax) }}</td>
                </tr>
            @endif
            @if($order->discount > 0)
                <tr>
                    <td>Discount{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}</td>
                    <td style="text-align:right;">-{{ formatPrice($order->discount) }}</td>
                </tr>
            @endif
            <tr>
                <td>Grand Total</td>
                <td style="text-align:right;">{{ formatPrice($order->grand_total) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Thank you for shopping with {{ config('app.name') }} 🌿 • {{ $order->payment_method }} payment
        @if($order->payment_status === 'paid' && $order->transaction_id)
            • Transaction: {{ $order->transaction_id }}
        @endif
    </div>
</div>
</body>
</html>
