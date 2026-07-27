<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function created(Product $product): void
    {
        logActivity('Created', 'Product', "Product '{$product->name}' was created.");
    }

    public function updated(Product $product): void
    {
        $changed = $product->getChanges();
        unset($changed['updated_at']);
        if (empty($changed)) return;

        $fields = array_keys($changed);
        logActivity('Updated', 'Product', "Product '{$product->name}' was updated. (" . implode(', ', $fields) . ')');

        if (isset($changed['stock'])) {
            $this->checkStockNotification($product);
        }
    }

    public function deleted(Product $product): void
    {
        logActivity('Deleted', 'Product', "Product '{$product->name}' was deleted.");
    }

    public function restored(Product $product): void
    {
        logActivity('Restored', 'Product', "Product '{$product->name}' was restored.");
    }

    protected function checkStockNotification(Product $product): void
    {
        $ns = app(\App\Services\NotificationService::class);
        if ($product->stock <= 0) {
            $ns->create(
                'out_of_stock',
                'Product',
                "Out of Stock: {$product->name}",
                "Product '{$product->name}' ({$product->sku}) is out of stock."
            );
        } elseif ($product->is_low_stock) {
            $ns->create(
                'low_stock',
                'Product',
                "Low Stock: {$product->name}",
                "Product '{$product->name}' ({$product->sku}) has only {$product->stock} units left."
            );
        }
    }
}
