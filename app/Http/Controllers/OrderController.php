<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::with(['items.product.category'])
            ->withCount('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product.category']);

        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product.category']);

        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('invoice-' . ($order->invoice_no ?? $order->id) . '.pdf');
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.success', compact('order'));
    }

    public function failed()
    {
        return view('orders.failed');
    }
}
