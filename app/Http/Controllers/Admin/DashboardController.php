<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\OrderService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(InventoryService $inventory, OrderService $orderService)
    {
        $totalProducts  = Product::count();
        $totalCategories = Category::count();
        $totalOrders    = Order::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $revenue        = $orderService->getRevenue();
        $lowStockCount  = $inventory->getLowStockCount();
        $lowStockProducts = $inventory->getLowStockProducts(10);
        $recentOrders   = Order::with('user')->latest()->limit(10)->get();
        $latestProducts = Product::with('category')->latest()->limit(10)->get();
        $recentNotifications = Notification::latest()->limit(5)->get()->map(function ($n) {
            $n->is_read = $n->isReadBy(auth()->user());
            return $n;
        });

        // Inventory stats
        $totalStockQty = Product::sum('stock');
        $outOfStockCount = Product::where('stock', 0)->count();
        $stockValue = Product::selectRaw('SUM(stock * COALESCE(buying_price, 0)) as value')->value('value');
        $todayStockIn = InventoryTransaction::where('type', 'in')->whereDate('date', today())->sum('quantity');
        $todayStockOut = InventoryTransaction::where('type', 'out')->whereDate('date', today())->sum('quantity');

        $currencySymbol = getCurrencySymbol();

        $months = collect();
        $monthlyRevenue = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));
            $monthlyRevenue->push(Order::whereIn('status', ['delivered', 'completed'])
                ->whereYear('ordered_at', $date->year)
                ->whereMonth('ordered_at', $date->month)
                ->sum('grand_total'));
        }

        $weeklySales = collect();
        $weekLabels = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weekLabels->push($date->format('D'));
            $weeklySales->push(Order::whereIn('status', ['delivered', 'completed'])
                ->whereDate('ordered_at', $date)
                ->sum('grand_total'));
        }

        return view('admin.dashboard', compact(
            'totalProducts', 'totalCategories', 'totalOrders',
            'totalCustomers', 'revenue', 'lowStockCount',
            'lowStockProducts', 'recentOrders', 'latestProducts',
            'months', 'monthlyRevenue', 'weekLabels', 'weeklySales',
            'currencySymbol', 'totalStockQty', 'outOfStockCount',
            'stockValue', 'todayStockIn', 'todayStockOut',
            'recentNotifications'
        ));
    }
}
