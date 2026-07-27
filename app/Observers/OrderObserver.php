<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        $ns = app(\App\Services\NotificationService::class);
        $ns->create(
            'new_order',
            'Order',
            "New Order #" . ($order->invoice_no ?? $order->id),
            "A new order of " . getCurrencySymbol() . number_format($order->grand_total, 2) . " was placed by {$order->customer_name}."
        );
    }

    public function updated(Order $order): void
    {
        $changed = $order->getChanges();
        if (isset($changed['status']) && in_array($changed['status'], ['cancelled', 'returned'])) {
            $ns = app(\App\Services\NotificationService::class);
            $ns->create(
                'order_cancelled',
                'Order',
                "Order #" . ($order->invoice_no ?? $order->id) . " Cancelled",
                "Order #" . ($order->invoice_no ?? $order->id) . " by {$order->customer_name} was marked as {$changed['status']}."
            );
        }
    }
}
