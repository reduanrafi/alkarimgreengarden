<?php

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
