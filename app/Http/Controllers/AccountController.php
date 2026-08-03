<?php

namespace App\Http\Controllers;

use App\Services\CartService;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CartService $cart)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isSeller()) {
            return redirect()->intended(route('seller.dashboard'));
        }

        $ordersCount = $user->orders()->count();
        $wishlistCount = $user->wishlists()->count();
        $addressesCount = $user->addresses()->count();
        $totalSpent = $user->totalSpent();
        $cartCount = $cart->getCount();

        $recentOrders = $user->orders()
            ->with(['items.product.category'])
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('account.index', compact(
            'ordersCount',
            'wishlistCount',
            'addressesCount',
            'totalSpent',
            'cartCount',
            'recentOrders',
        ));
    }
}
