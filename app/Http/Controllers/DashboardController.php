<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $bread_crumbs = [
            'Dashboard' => route('admin.dashboard'),
            'Orders' => route('admin.orders'),
        ];
        $totalOrders = Order::where('user_id', Auth::id())->count();
        $pendingOrders = Order::where('user_id', Auth::id())->where('status', 'pending')->count();
        $completedOrders = Order::where('user_id', Auth::id())->where('status', 'completed')->count();
        $cancelledOrders = Order::where('user_id', Auth::id())->where('cancellation_status', 'Approved')->count();

        $recentOrders = Order::where('user_id', Auth::id())->latest()->take(10)->get();

        return view('dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'recentOrders',
            'page_title',
            'bread_crumbs'
        ));
    }
}
