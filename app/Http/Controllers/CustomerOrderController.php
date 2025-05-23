<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $bread_crumbs = [
            'Dashboard' => route('dashboard'),
            'Orders' => route('customer.orders'),
        ];
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('customer.orders.index', compact('orders', 'page_title', 'bread_crumbs'));
    }

    public function show(Order $order)
    {
        $page_title = 'Dashboard';
        $bread_crumbs = [
            'Dashboard' => route('dashboard'),
            'Orders' => route('customer.orders'),
            'Details' => '#',
        ];
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        return view('customer.orders.show', compact('order', 'page_title', 'bread_crumbs'));
    }
}
