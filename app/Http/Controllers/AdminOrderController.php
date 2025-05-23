<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Services\ShopifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $bread_crumbs = [
            'Dashboard' => route('admin.dashboard'),
            'Orders' => route('admin.orders'),
        ];
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders', 'page_title', 'bread_crumbs'));
    }

    public function updateStatus(Request $request, Order $order, ShopifyService $shopifyService)
    {
        $request->validate(['current_stage' => 'required|string']);

        $order->update(['current_stage' => $request->current_stage]);

        // Update Shopify order status
        $shopifyService->updateOrderStatus($order);
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order));

        return back()->with('success', 'Order status updated successfully.');
    }
}
