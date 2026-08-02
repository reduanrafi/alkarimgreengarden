<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    const STATUSES = [
        'pending', 'confirmed', 'processing', 'packed',
        'shipped', 'delivered', 'completed', 'cancelled', 'returned', 'refunded',
    ];

    const PAYMENT_STATUSES = ['pending', 'paid', 'unpaid', 'completed', 'refunded'];

    const PAYMENT_METHODS = [
        'cod' => 'Cash On Delivery',
        'sslcommerz' => 'SSLCommerz',
        'bkash' => 'bKash',
        'nagad' => 'Nagad',
        'rocket' => 'Rocket',
        'card' => 'Card',
    ];

    const STATUS_LABELS = [
        'pending'    => 'Pending',
        'confirmed'  => 'Confirmed',
        'processing' => 'Processing',
        'packed'     => 'Packed',
        'shipped'    => 'Shipped',
        'delivered'  => 'Delivered',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
        'returned'   => 'Returned',
        'refunded'   => 'Refunded',
    ];

    public function getStatuses(): array
    {
        return self::STATUSES;
    }

    public function getPaymentStatuses(): array
    {
        return self::PAYMENT_STATUSES;
    }

    public function getPaymentMethods(): array
    {
        return self::PAYMENT_METHODS;
    }

    public function getStatusLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? ucfirst($status);
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $timeline = $order->status_timeline ?? [];
        $timeline[$status] = now()->toDateTimeString();
        $order->update(['status' => $status, 'status_timeline' => $timeline]);

        return $order;
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus): Order
    {
        $order->update(['payment_status' => $paymentStatus]);

        return $order;
    }

    public function getRevenue(): float
    {
        return Order::whereIn('status', ['delivered', 'completed'])
            ->sum('grand_total');
    }

    public function getMonthlyRevenue(int $year, int $month): float
    {
        return Order::whereIn('status', ['delivered', 'completed'])
            ->whereYear('ordered_at', $year)
            ->whereMonth('ordered_at', $month)
            ->sum('grand_total');
    }

    public function getOrderTotalsByStatus(): array
    {
        return [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
    }
}
