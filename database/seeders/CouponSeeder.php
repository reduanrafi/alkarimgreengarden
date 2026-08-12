<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['code' => 'GROW10', 'type' => 'percentage', 'value' => 10, 'min_order_amount' => 30, 'usage_limit' => 200, 'used_count' => 0, 'expiry_date' => now()->addYear()->toDateString(), 'status' => true],
            ['code' => 'PLANTCARE15', 'type' => 'fixed', 'value' => 15, 'min_order_amount' => 75, 'usage_limit' => 100, 'used_count' => 0, 'expiry_date' => now()->addYear()->toDateString(), 'status' => true],
            ['code' => 'WELCOMELEAF', 'type' => 'percentage', 'value' => 15, 'min_order_amount' => 50, 'usage_limit' => 300, 'used_count' => 0, 'expiry_date' => now()->addYear()->toDateString(), 'status' => true],
        ] as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
