<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $page_title = 'Reports';
        $bread_crumbs = [
            'Dashboard' => route('admin.dashboard'),
            'Reports' => route('admin.reports'),
        ];

        $totalSales = Order::where('status', 'paid')->sum('total_price');
        $totalOrders = Order::count();
        $totalCustomers = User::count();
        $monthlySales = Order::where('status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');
        $topCustomers = User::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'page_title',
            'bread_crumbs',
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'monthlySales',
            'topCustomers'
        ));
    }
}
