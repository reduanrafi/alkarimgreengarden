<?php

if (!function_exists('settings')) {
    function settings(): array
    {
        return cache()->remember('app_settings', 3600, function () {
            return \App\Models\Setting::pluck('value', 'key')->all();
        });
    }
}

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return settings()[$key] ?? $default;
    }
}

if (!function_exists('getActiveCurrency')) {
    function getActiveCurrency(): ?\App\Models\CurrencySetting
    {
        return cache()->remember('active_currency', 3600, function () {
            return \App\Models\CurrencySetting::where('status', true)->first()
                ?? \App\Models\CurrencySetting::where('is_default', true)->first();
        });
    }
}

if (!function_exists('getCurrencySymbol')) {
    function getCurrencySymbol(): string
    {
        return getActiveCurrency()?->symbol ?? '$';
    }
}

if (!function_exists('logActivity')) {
    function logActivity(string $action, string $module, ?string $description = null): \App\Models\ActivityLog
    {
        return app(\App\Services\ActivityLoggerService::class)->log($action, $module, $description);
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice(float|int $amount): string
    {
        $currency = getActiveCurrency();

        if (!$currency) {
            return '$' . number_format($amount, 2);
        }

        $converted = $amount * $currency->exchange_rate;

        $decimals = in_array($currency->currency_code, ['BDT', 'INR']) ? 0 : 2;

        return $currency->symbol . ' ' . number_format($converted, $decimals);
    }
}

if (!function_exists('categoryEmoji')) {
    function categoryEmoji(?string $slug, ?string $name = null): string
    {
        $slug = strtolower((string) $slug);
        $name = strtolower((string) $name);

        if (str_contains($slug, 'mens') || str_contains($name, 'men')) {
            return '👕';
        }
        if (str_contains($slug, 'womens') || str_contains($name, 'women')) {
            return '👚';
        }
        if (str_contains($slug, 'bag') || str_contains($name, 'bag')) {
            return '👜';
        }
        if (str_contains($slug, 'indoor') || str_contains($name, 'indoor')) {
            return '🪴';
        }
        if (str_contains($slug, 'outdoor') || str_contains($name, 'outdoor')) {
            return '🌳';
        }
        if (str_contains($slug, 'succulent') || str_contains($slug, 'cact')) {
            return '🌵';
        }
        if (str_contains($slug, 'flower') || str_contains($name, 'flower')) {
            return '🌸';
        }
        if (str_contains($slug, 'planter') || str_contains($slug, 'pot')) {
            return '🏺';
        }
        if (str_contains($slug, 'gift')) {
            return '🎁';
        }
        if (str_contains($slug, 'others') || str_contains($slug, 'other')) {
            return '🌿';
        }

        return '🌿';
    }
}
