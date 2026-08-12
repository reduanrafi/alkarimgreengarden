<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::whereIn('status', ['delivered', 'completed'])->sum('grand_total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('is_admin', false)->count();

        $revenueByMonth = Order::whereIn('status', ['delivered', 'completed'])
            ->selectRaw($this->formatDateSql('ordered_at', '%Y-%m') . " as month, SUM(grand_total) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
            ->with('product:id,name,price,image')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(fn($item) => $item->product ? (object)[
                'name'       => $item->product->name,
                'price'      => $item->product->price,
                'image'      => $item->product->image,
                'total_sold' => $item->total_sold,
            ] : null)
            ->filter();

        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->with('category:id,name')
            ->orderBy('stock')
            ->take(5)
            ->get(['id', 'name', 'stock', 'category_id']);

        return view('admin.reports.index', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'totalCustomers',
            'revenueByMonth', 'ordersByStatus', 'topProducts', 'lowStockProducts'
        ));
    }

    public function sales(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $query = Order::whereIn('status', ['delivered', 'completed']);

        if ($period === 'daily') {
            $salesData = (clone $query)
                ->selectRaw($this->formatDateSql('ordered_at', '%Y-%m-%d') . " as label, SUM(grand_total) as total, COUNT(*) as count")
                ->where('ordered_at', '>=', now()->subDays(30))
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } elseif ($period === 'yearly') {
            $salesData = (clone $query)
                ->selectRaw($this->formatDateSql('ordered_at', '%Y') . " as label, SUM(grand_total) as total, COUNT(*) as count")
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } else {
            $salesData = (clone $query)
                ->selectRaw($this->formatDateSql('ordered_at', '%Y-%m') . " as label, SUM(grand_total) as total, COUNT(*) as count")
                ->where('ordered_at', '>=', now()->subMonths(12))
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        }

        $totalRevenue = (clone $query)->sum('grand_total');
        $totalOrders = (clone $query)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin.reports.sales', compact('salesData', 'period', 'totalRevenue', 'totalOrders', 'avgOrderValue'));
    }

    public function orders(Request $request)
    {
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Order::with('user:id,name');

        if ($status) {
            $query->where('status', $status);
        }
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }
        if ($dateFrom) {
            $query->whereDate('ordered_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('ordered_at', '<=', $dateTo);
        }

        $orders = $query->orderByDesc('ordered_at')->paginate(15)->withQueryString();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status');
        $ordersByPayment = Order::selectRaw('payment_status, COUNT(*) as count')
            ->groupBy('payment_status')->pluck('count', 'payment_status');

        return view('admin.reports.orders', compact('orders', 'ordersByStatus', 'ordersByPayment', 'status', 'paymentStatus', 'dateFrom', 'dateTo'));
    }

    public function products(Request $request)
    {
        $sort = $request->get('sort', 'revenue');

        $query = OrderItem::selectRaw('
                product_id,
                SUM(quantity) as total_sold,
                SUM(price * quantity) as total_revenue
            ')
            ->with('product:id,name,price,stock,image,category_id')
            ->groupBy('product_id');

        if ($sort === 'qty') {
            $query->orderByDesc('total_sold');
        } else {
            $query->orderByDesc('total_revenue');
        }

        $items = $query->paginate(15)->withQueryString();

        $summary = Product::selectRaw('
                COUNT(*) as total_products,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_products,
                SUM(stock) as total_stock,
                AVG(price) as avg_price
            ')->first();

        return view('admin.reports.products', compact('items', 'sort', 'summary'));
    }

    public function categories()
    {
        $categories = Category::withCount('products')
            ->withSum(['products as total_stock' => fn($q) => $q], 'stock')
            ->get()
            ->map(function ($cat) {
                $revenue = OrderItem::whereHas('product', fn($q) => $q->where('category_id', $cat->id))
                    ->whereHas('order', fn($q) => $q->whereIn('status', ['delivered', 'completed']))
                    ->selectRaw('SUM(price * quantity) as rev')
                    ->value('rev') ?? 0;
                $cat->revenue = $revenue;
                return $cat;
            })
            ->sortByDesc('revenue');

        $totalRevenue = $categories->sum('revenue');

        return view('admin.reports.categories', compact('categories', 'totalRevenue'));
    }

    public function customers(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $query = User::where('is_admin', false);

        if ($period === 'daily') {
            $registrations = (clone $query)
                ->selectRaw($this->formatDateSql('created_at', '%Y-%m-%d') . " as label, COUNT(*) as count")
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('label')->orderBy('label')->get();
        } elseif ($period === 'yearly') {
            $registrations = (clone $query)
                ->selectRaw($this->formatDateSql('created_at', '%Y') . " as label, COUNT(*) as count")
                ->groupBy('label')->orderBy('label')->get();
        } else {
            $registrations = (clone $query)
                ->selectRaw($this->formatDateSql('created_at', '%Y-%m') . " as label, COUNT(*) as count")
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('label')->orderBy('label')->get();
        }

        $totalCustomers = (clone $query)->count();
        $blockedCustomers = (clone $query)->where('status', 'blocked')->count();
        $verifiedCustomers = (clone $query)->whereNotNull('email_verified_at')->count();

        $topCustomers = Order::whereIn('status', ['delivered', 'completed'])
            ->selectRaw('user_id, SUM(grand_total) as total_spent, COUNT(*) as order_count')
            ->with('user:id,name,email,photo')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get()
            ->map(fn($item) => $item->user ? (object)[
                'name'        => $item->user->name,
                'email'       => $item->user->email,
                'photo'       => $item->user->photo,
                'total_spent' => $item->total_spent,
                'order_count' => $item->order_count,
            ] : null)
            ->filter();

        return view('admin.reports.customers', compact(
            'registrations', 'period', 'totalCustomers', 'blockedCustomers', 'verifiedCustomers', 'topCustomers'
        ));
    }

    public function payments()
    {
        $byMethod = Order::selectRaw('payment_method, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $byStatus = Order::selectRaw('payment_status, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('payment_status')
            ->orderByDesc('total')
            ->get();

        $monthly = Order::whereIn('status', ['delivered', 'completed'])
            ->selectRaw($this->formatDateSql('ordered_at', '%Y-%m') . " as label, SUM(grand_total) as total")
            ->where('ordered_at', '>=', now()->subMonths(12))
            ->groupBy('label')->orderBy('label')->get();

        $totalCollected = Order::whereIn('payment_status', ['paid', 'completed'])->sum('grand_total');
        $totalPending = Order::where('payment_status', 'pending')->sum('grand_total');
        $totalFailed = Order::where('payment_status', 'failed')->sum('grand_total');

        return view('admin.reports.payments', compact(
            'byMethod', 'byStatus', 'monthly',
            'totalCollected', 'totalPending', 'totalFailed'
        ));
    }

    public function discounts()
    {
        $totalDiscount = Order::sum('discount');
        $totalOrdersWithDiscount = Order::where('discount', '>', 0)->count();
        $avgDiscount = Order::where('discount', '>', 0)->avg('discount');

        $coupons = Coupon::orderByDesc('used_count')->get();

        $monthly = Order::selectRaw($this->formatDateSql('ordered_at', '%Y-%m') . " as label, SUM(discount) as total")
            ->where('discount', '>', 0)
            ->where('ordered_at', '>=', now()->subMonths(12))
            ->groupBy('label')->orderBy('label')->get();

        return view('admin.reports.discounts', compact('totalDiscount', 'totalOrdersWithDiscount', 'avgDiscount', 'coupons', 'monthly'));
    }

    private function formatDateSql(string $column, string $format): string
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return "strftime('{$format}', {$column})";
        }
        return "DATE_FORMAT({$column}, '{$format}')";
    }
}
