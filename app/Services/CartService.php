<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function getCart(): array
    {
        $cart = $this->storageGet('cart', []);
        return $this->enrichCart($cart);
    }

    public function getRawCart(): array
    {
        return $this->storageGet('cart', []);
    }

    protected function enrichCart(array $cart): array
    {
        if (empty($cart)) return [];

        $ids = array_keys($cart);
        $products = Product::with('category')->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($cart as $id => &$item) {
            $product = $products->get($id);
            if ($product) {
                $item['id'] = (int) $product->id;
                $item['name'] = $product->name;
                $item['price'] = (float) $product->price;
                $item['slug'] = $product->slug;
                $item['stock'] = $product->stock;
                $item['category_slug'] = $product->category->slug ?? '';
                $item['image'] = $product->image;
                $item['brand'] = $product->brand;
                $item['sku'] = $product->sku ?? 'FSN-' . str_pad($product->id, 5, '0', STR_PAD_LEFT);
                $item['discount_price'] = $product->discount_price ? (float) $product->discount_price : null;
                $item['discount_type'] = $product->discount_type;
                $item['final_price'] = (float) $product->final_price;
                $item['stock_status'] = $product->stock_status;
                $item['category_name'] = $product->category->name ?? '';
            }
        }

        return $cart;
    }

    public function add(Product $product, int $quantity): void
    {
        $cart = $this->storageGet('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = min($cart[$id]['quantity'] + $quantity, $product->stock);
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => min($quantity, $product->stock),
                'slug' => $product->slug,
                'stock' => $product->stock,
                'category_slug' => $product->category->slug ?? '',
            ];
        }

        $this->storagePut('cart', $cart);
    }

    public function update(int $id, int $quantity): void
    {
        $cart = $this->storageGet('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, min($quantity, $cart[$id]['stock']));
        }
        $this->storagePut('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->storageGet('cart', []);
        unset($cart[$id]);
        $this->storagePut('cart', $cart);
    }

    public function clear(): void
    {
        $this->storageForget('cart');
    }

    public function getTotal(): float
    {
        $cart = $this->getRawCart();
        return array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
    }

    public function getSubtotal(): float
    {
        $cart = $this->getCart();
        return array_sum(array_map(fn ($item) => ($item['final_price'] ?? $item['price']) * $item['quantity'], $cart));
    }

    public function getCount(): int
    {
        return array_sum(array_column($this->getRawCart(), 'quantity'));
    }

    protected function storageGet(string $key, mixed $default = null): mixed
    {
        return session()->get($key, $default);
    }

    protected function storagePut(string $key, mixed $value): void
    {
        session()->put($key, $value);
    }

    protected function storageForget(string $key): void
    {
        session()->forget($key);
    }

    public function getCoupon(): array
    {
        return $this->storageGet('coupon', []);
    }

    public function setCoupon(array $coupon): void
    {
        $this->storagePut('coupon', $coupon);
    }

    public function removeCoupon(): void
    {
        $this->storageForget('coupon');
    }
}
