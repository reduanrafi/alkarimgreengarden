<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function getCart(): array
    {
        $cart = session()->get('cart', []);
        return $this->enrichCart($cart);
    }

    public function getRawCart(): array
    {
        return session()->get('cart', []);
    }

    protected function enrichCart(array $cart): array
    {
        if (empty($cart)) return [];

        $ids = array_keys($cart);
        $products = Product::with('category')->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($cart as $id => &$item) {
            $product = $products->get($id);
            if ($product) {
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
        $cart = session()->get('cart', []);
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

        session()->put('cart', $cart);
    }

    public function update(int $id, int $quantity): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, min($quantity, $cart[$id]['stock']));
        }
        session()->put('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function getTotal(): float
    {
        $cart = $this->getRawCart();
        return array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
    }

    public function getCount(): int
    {
        return array_sum(array_column($this->getRawCart(), 'quantity'));
    }
}
