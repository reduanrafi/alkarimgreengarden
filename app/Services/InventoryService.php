<?php

namespace App\Services;

use App\Models\Product;

class InventoryService
{
    public function getLowStockCount(): int
    {
        return Product::lowStock()->count();
    }

    public function getOutOfStockCount(): int
    {
        return Product::outOfStock()->count();
    }

    public function getLowStockProducts(int $limit = 10)
    {
        return Product::with('category')
            ->lowStock()
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }

    public function getTotalStockValue(): float
    {
        return Product::where('status', true)->get()->sum(function ($product) {
            return $product->stock * ($product->discount_price ?: $product->price);
        });
    }

    public function getTopSellingProducts(int $limit = 10)
    {
        return Product::whereHas('orderItems')
            ->withCount(['orderItems as total_sold' => function ($q) {
                $q->select(\DB::raw('COALESCE(SUM(quantity), 0)'));
            }])
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}
