<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CurrencySettingController as AdminCurrencySettingController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\AttributeValueController as AdminAttributeValueController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user?->isAdmin()) {
        return redirect()->intended(route('admin.dashboard'));
    }
    if ($user?->isSeller()) {
        return redirect()->intended(route('seller.dashboard'));
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout (blocked users cannot checkout)
    Route::middleware('blocked')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Orders (blocked users cannot place new orders)
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('orders.success');
        Route::get('/order/failed', [OrderController::class, 'failed'])->name('orders.failed');
    });

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/move-to-cart/{product}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Reviews
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Coupons
    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{product}/delete-gallery/{index}', [AdminProductController::class, 'deleteGalleryImage'])->name('products.deleteGalleryImage');
    Route::post('/products/bulk-update-stock', [AdminProductController::class, 'bulkUpdateStock'])->name('products.bulk-update-stock');

    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/stock-in', [AdminInventoryController::class, 'stockInForm'])->name('inventory.stock-in');
    Route::post('/inventory/stock-in', [AdminInventoryController::class, 'stockIn'])->name('inventory.stock-in.store');
    Route::get('/inventory/stock-out', [AdminInventoryController::class, 'stockOutForm'])->name('inventory.stock-out');
    Route::post('/inventory/stock-out', [AdminInventoryController::class, 'stockOut'])->name('inventory.stock-out.store');
    Route::get('/inventory/adjust', [AdminInventoryController::class, 'adjustForm'])->name('inventory.adjust');
    Route::post('/inventory/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust.store');
    Route::get('/inventory/history', [AdminInventoryController::class, 'history'])->name('inventory.history');
    Route::get('/inventory/export/csv', [AdminInventoryController::class, 'exportCsv'])->name('inventory.export.csv');

    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export/csv', [AdminActivityLogController::class, 'exportCsv'])->name('activity-logs.export.csv');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/{notification}/delete', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/clear-all', [AdminNotificationController::class, 'clearAll'])->name('notifications.clear-all');

    Route::get('/attributes', [AdminAttributeController::class, 'index'])->name('attributes.index');
    Route::get('/attributes/create', [AdminAttributeController::class, 'create'])->name('attributes.create');
    Route::post('/attributes', [AdminAttributeController::class, 'store'])->name('attributes.store');
    Route::get('/attributes/{attribute}/edit', [AdminAttributeController::class, 'edit'])->name('attributes.edit');
    Route::put('/attributes/{attribute}', [AdminAttributeController::class, 'update'])->name('attributes.update');
    Route::delete('/attributes/{attribute}', [AdminAttributeController::class, 'destroy'])->name('attributes.destroy');

    Route::get('/attributes/{attribute}/values', [AdminAttributeValueController::class, 'index'])->name('attributes.values');
    Route::post('/attributes/{attribute}/values', [AdminAttributeValueController::class, 'store'])->name('attributes.values.store');
    Route::put('/attributes/{attribute}/values/{attributeValue}', [AdminAttributeValueController::class, 'update'])->name('attributes.values.update');
    Route::delete('/attributes/{attribute}/values/{attributeValue}', [AdminAttributeValueController::class, 'destroy'])->name('attributes.values.destroy');
    Route::post('/attributes/{attribute}/values/sort-order', [AdminAttributeValueController::class, 'updateSortOrder'])->name('attributes.values.sort-order');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export/csv', [AdminOrderController::class, 'exportCsv'])->name('orders.export.csv');
    Route::get('/orders/export/excel', [AdminOrderController::class, 'exportExcel'])->name('orders.export.excel');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/statuses', [AdminOrderController::class, 'statuses'])->name('orders.statuses');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{order}/pdf', [AdminOrderController::class, 'pdf'])->name('orders.pdf');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [AdminCustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [AdminCustomerController::class, 'update'])->name('customers.update');
    Route::patch('/customers/{customer}/block', [AdminCustomerController::class, 'block'])->name('customers.block');
    Route::patch('/customers/{customer}/unblock', [AdminCustomerController::class, 'unblock'])->name('customers.unblock');
    Route::patch('/customers/{customer}/activate', [AdminCustomerController::class, 'activate'])->name('customers.activate');
    Route::patch('/customers/{customer}/deactivate', [AdminCustomerController::class, 'deactivate'])->name('customers.deactivate');
    Route::delete('/customers/{customer}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/customers/export/csv', [AdminCustomerController::class, 'exportCsv'])->name('customers.export.csv');
    Route::get('/customers/export/excel', [AdminCustomerController::class, 'exportExcel'])->name('customers.export.excel');
    Route::post('/customers/bulk', [AdminCustomerController::class, 'bulkAction'])->name('customers.bulk');

    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/orders', [\App\Http\Controllers\Admin\ReportController::class, 'orders'])->name('reports.orders');
    Route::get('/reports/products', [\App\Http\Controllers\Admin\ReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/categories', [\App\Http\Controllers\Admin\ReportController::class, 'categories'])->name('reports.categories');
    Route::get('/reports/customers', [\App\Http\Controllers\Admin\ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/payments', [\App\Http\Controllers\Admin\ReportController::class, 'payments'])->name('reports.payments');
    Route::get('/reports/discounts', [\App\Http\Controllers\Admin\ReportController::class, 'discounts'])->name('reports.discounts');

    Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [AdminBannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [AdminBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');
    Route::post('/banners/{banner}/toggle', [AdminBannerController::class, 'toggle'])->name('banners.toggle');

    Route::get('/currency-settings', [AdminCurrencySettingController::class, 'index'])->name('currency-settings.index');
    Route::get('/currency-settings/create', [AdminCurrencySettingController::class, 'create'])->name('currency-settings.create');
    Route::post('/currency-settings', [AdminCurrencySettingController::class, 'store'])->name('currency-settings.store');
    Route::get('/currency-settings/{currencySetting}/edit', [AdminCurrencySettingController::class, 'edit'])->name('currency-settings.edit');
    Route::put('/currency-settings/{currencySetting}', [AdminCurrencySettingController::class, 'update'])->name('currency-settings.update');
    Route::delete('/currency-settings/{currencySetting}', [AdminCurrencySettingController::class, 'destroy'])->name('currency-settings.destroy');
    Route::post('/currency-settings/{currencySetting}/toggle', [AdminCurrencySettingController::class, 'toggle'])->name('currency-settings.toggle');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // Global search
    Route::get('/search', function (\Illuminate\Http\Request $request) {
        $query = $request->get('q');
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        $products = \App\Models\Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(8)->get(['id', 'name', 'slug']);
        return response()->json($products->map(fn ($p) => [
            'name' => $p->name,
            'url' => route('admin.products.edit', $p),
        ]));
    })->name('search');
});

require __DIR__.'/auth.php';
