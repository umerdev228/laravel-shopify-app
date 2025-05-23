<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCancellationController extends Controller
{
    public function requestCancellation(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized request.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order->update([
            'cancellation_status' => 'Pending',
            'cancellation_reason' => $request->reason,
        ]);

        return back()->with('success', 'Cancellation request submitted.');
    }

    public function approve(Request $request, Order $order)
    {
        $order->update(['cancellation_status' => 'Approved']);
        return back()->with('success', 'Order cancellation approved.');
    }

    public function reject(Request $request, Order $order)
    {
        $order->update(['cancellation_status' => 'Rejected']);
        return back()->with('success', 'Order cancellation rejected.');
    }
}
