<?php

namespace App\Observers;

use App\Models\Coupon;

class CouponObserver
{
    public function created(Coupon $coupon): void
    {
        logActivity('Created', 'Coupon', "Coupon '{$coupon->code}' was created.");

        $ns = app(\App\Services\NotificationService::class);
        $ns->create('new_coupon', 'Coupon', "New Coupon: {$coupon->code}", "Coupon '{$coupon->code}' was created with " . ($coupon->discount_type === 'percentage' ? $coupon->discount_value . '% off' : getCurrencySymbol() . $coupon->discount_value . ' off') . '.');
    }

    public function updated(Coupon $coupon): void
    {
        $changed = $coupon->getChanges();
        unset($changed['updated_at']);
        if (empty($changed)) return;

        $fields = array_keys($changed);
        logActivity('Updated', 'Coupon', "Coupon '{$coupon->code}' was updated. (" . implode(', ', $fields) . ')');
    }

    public function deleted(Coupon $coupon): void
    {
        logActivity('Deleted', 'Coupon', "Coupon '{$coupon->code}' was deleted.");
    }
}
