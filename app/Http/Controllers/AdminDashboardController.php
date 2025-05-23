<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $bread_crumbs = [
            'Dashboard' => route('admin.dashboard'),
            'Orders' => route('admin.orders'),
        ];
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('cancellation_status', 'Approved')->count();
        $totalCustomers = User::whereNotNull('email')->count();

        $recentOrders = Order::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalCustomers',
            'recentOrders',
            'page_title',
            'bread_crumbs'
        ));
    }
}
