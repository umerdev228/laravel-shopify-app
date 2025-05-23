<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderSyncService
{
    public function syncOrders()
    {
        $shop = config('shopify.shop_url');
        $accessToken = config('shopify.access_token');

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://{$shop}/admin/api/2025-01/orders.json", [
            'status' => 'any',
            'financial_status' => 'paid',
            'fulfillment_status' => 'fulfilled',
            'limit' => 50,
            'created_at_min' => now()->subYear()->toIso8601String(),
        ]);

        if ($response->successful()) {
            $orders = $response->json()['orders'];

            foreach ($orders as $order) {
                // Ensure a customer exists
                if (isset($order['customer'])) {
                    $customer = User::updateOrCreate(
                        ['email' => $order['customer']['email']],
                        ['name' => $order['customer']['first_name'] ?? 'Guest']
                    );
                    $userId = $customer->id;
                } else {
                    $userId = 1;
                }

                // Save Order
                Order::updateOrCreate(
                    ['order_id' => $order['id']],
                    [
                        'user_id' => $userId,
                        'order_number' => $order['name'] ?? null,
                        'status' => $order['financial_status'],
                        'total_price' => $order['total_price'] ?? 0.00,
                        'currency' => $order['currency'] ?? 'USD',
                        'shipping_address' => isset($order['shipping_address']) ? json_encode($order['shipping_address']) : null,
                        'billing_address' => isset($order['billing_address']) ? json_encode($order['billing_address']) : null,
                        'line_items' => isset($order['line_items']) ? json_encode($order['line_items']) : null,
                        'fulfillment_status' => $order['fulfillment_status'] ?? 'pending',
                        'stages' => json_encode([
                            'stage_1' => 'Order Received',
                            'stage_2' => 'Processing',
                            'stage_3' => 'Completed',
                        ]),
                    ]
                );
            }
        }
    }

}
