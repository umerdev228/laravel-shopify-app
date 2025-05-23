<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderSyncService;

class OrderController extends Controller
{
    protected $orderSyncService;

    public function __construct(OrderSyncService $orderSyncService)
    {
        $this->orderSyncService = $orderSyncService;
    }

    public function sync()
    {
        $this->orderSyncService->syncOrders();
        return response()->json(['message' => 'Orders synchronized successfully!']);
    }
}
