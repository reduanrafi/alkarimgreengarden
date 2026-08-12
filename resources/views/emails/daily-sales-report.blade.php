<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Sales Report</title>
</head>
<body style="margin:0;background:#f5f7f5;color:#24332a;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:600px;margin:0 auto;padding:32px 16px;">
        <div style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
            <div style="padding:24px 32px;background:#1f5c3f;color:#ffffff;">
                <h1 style="margin:0;font-size:24px;">Daily Sales Report</h1>
                <p style="margin:8px 0 0;">{{ \Carbon\Carbon::parse($reportDate)->toFormattedDateString() }}</p>
            </div>

            <div style="padding:24px 32px;">
                <table role="presentation" style="width:100%;border-collapse:collapse;font-size:15px;">
                    <tr><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;color:#6b7280;">Total orders today</td><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;text-align:right;font-weight:600;">{{ $summary['total_orders'] }}</td></tr>
                    <tr><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;color:#6b7280;">Completed orders</td><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;text-align:right;font-weight:600;">{{ $summary['completed_orders'] }}</td></tr>
                    <tr><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;color:#6b7280;">Pending orders</td><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;text-align:right;font-weight:600;">{{ $summary['pending_orders'] }}</td></tr>
                    <tr><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;color:#6b7280;">Cancelled orders</td><td style="padding:12px 0;border-bottom:1px solid #e5e7eb;text-align:right;font-weight:600;">{{ $summary['cancelled_orders'] }}</td></tr>
                    <tr><td style="padding:12px 0;color:#6b7280;">Total sales today</td><td style="padding:12px 0;text-align:right;font-weight:600;">{{ formatPrice($summary['total_sales']) }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
