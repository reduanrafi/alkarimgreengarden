<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApiCartService extends CartService
{
    protected function storageKey(): string
    {
        return 'cart_user_' . Auth::id();
    }

    protected function storageGet(string $key, mixed $default = null): mixed
    {
        return Cache::store('file')->get($this->storageKey() . '_' . $key, $default);
    }

    protected function storagePut(string $key, mixed $value): void
    {
        Cache::store('file')->put($this->storageKey() . '_' . $key, $value, 60 * 24 * 7);
    }

    protected function storageForget(string $key): void
    {
        Cache::store('file')->forget($this->storageKey() . '_' . $key);
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();

        return array_sum(array_map(
            fn ($item) => ($item['final_price'] ?? $item['price']) * $item['quantity'],
            $cart
        ));
    }
}
