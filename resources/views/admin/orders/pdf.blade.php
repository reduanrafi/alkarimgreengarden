<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->invoice_no ?? $order->id }} - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #10b981; }
        .store-name { font-size: 22px; font-weight: bold; color: #10b981; }
        .invoice-title { font-size: 26px; font-weight: bold; color: #0f172a; margin-top: 4px; }
        .meta { color: #64748b; font-size: 10px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th { background: #f1f5f9; text-align: left; padding: 8px 6px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; border-bottom: 2px solid #e2e8f0; }
        td { padding: 7px 6px; border-bottom: 1px solid #f1f5f9; }
        .totals { width: 280px; margin-left: auto; margin-top: 16px; }
        .totals td { padding: 4px 6px; }
        .totals tr:last-child td { border-top: 2px solid #10b981; font-size: 14px; font-weight: bold; color: #059669; }
        .footer { text-align: center; margin-top: 30px; padding-top: 16px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 9px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; background: #d1fae5; color: #059669; }
        .info-box { margin-bottom: 20px; }
        .info-box h3 { font-size: 11px; color: #0f172a; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.3px; }
        .info-box p { margin: 1px 0; color: #475569; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="store-name">{{ config('app.name') }}</div>
            <div class="invoice-title">INVOICE</div>
        </div>
        <div style="text-align:right;">
            <div class="badge">{{ ucfirst($order->status) }}</div>
            <div class="meta" style="margin-top: 6px;">
                <strong>Invoice #:</strong> {{ $order->invoice_no ?? 'INV-' . $order->id }}<br>
                <strong>Date:</strong> {{ $order->ordered_at?->format('M d, Y') ?? $order->created_at->format('M d, Y') }}<br>
                <strong>Order ID:</strong> #{{ $order->id }}
            </div>
        </div>
    </div>

    <table style="margin-bottom: 24px;">
        <tr>
            <td style="width: 50%; border: none; padding: 0; vertical-align: top;">
                <div class="info-box">
                    <h3>Bill To</h3>
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->email }}</p>
                    <p>{{ $order->phone }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->city ?? $order->district }}, {{ $order->country ?? $order->division }} {{ $order->postal_code }}</p>
                </div>
            </td>
            <td style="width: 50%; border: none; padding: 0; vertical-align: top;">
                <div class="info-box" style="text-align: right;">
                    <h3>Store Information</h3>
                    <p><strong>{{ config('app.name') }}</strong></p>
                    <p>{{ config('app.url') }}</p>
                    <p>{{ config('mail.from.address') }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Product</th>
                <th style="text-align: center;">Price</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                <td style="text-align: center;">{{ formatPrice($item->price) }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ formatPrice($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 8px;">
        <p style="font-size: 9px; color: #64748b; margin: 2px 0;"><strong>Payment Method:</strong> {{ str_replace('_', ' ', ucfirst($order->payment_method ?? 'N/A')) }}</p>
        <p style="font-size: 9px; color: #64748b; margin: 2px 0;"><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'N/A') }}</p>
        @if($order->transaction_id)
        <p style="font-size: 9px; color: #64748b; margin: 2px 0;"><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
        @endif
    </div>

    <table class="totals">
        <tr><td>Subtotal</td><td style="text-align: right;">{{ formatPrice($order->subtotal) }}</td></tr>
        @if($order->tax > 0)<tr><td>Tax</td><td style="text-align: right;">{{ formatPrice($order->tax) }}</td></tr>@endif
        <tr><td>Shipping</td><td style="text-align: right;">{{ formatPrice($order->shipping_charge) }}</td></tr>
        @if($order->discount > 0)
        <tr><td style="color: #059669;">Discount</td><td style="text-align: right; color: #059669;">-{{ formatPrice($order->discount) }}</td></tr>
        @endif
        @if($order->coupon_code)
        <tr><td style="color: #6366f1;">Coupon ({{ $order->coupon_code }})</td><td style="text-align: right; color: #6366f1;">-{{ formatPrice($order->discount) }}</td></tr>
        @endif
        <tr><td style="font-weight: bold;">Grand Total</td><td style="text-align: right; font-weight: bold;">{{ formatPrice($order->grand_total) }}</td></tr>
    </table>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>{{ config('app.name') }} &mdash; Powered by AlkarimGG</p>
    </div>
</body>
</html>
