<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    protected $shop;
    protected $accessToken;

    public function __construct()
    {
        $this->shop = config('shopify.shop_url');
        $this->accessToken = config('shopify.access_token');
    }

    public function updateOrderStatus(Order $order)
    {
        $shopifyOrderId = $order->order_id; // Shopify Order ID
        $status = $order->status; // Laravel order status

        $payload = [
            "order" => [
                "id" => $shopifyOrderId,
                "note" => "Order status updated to: $status",
                "financial_status" => $this->mapFinancialStatus($status),
                "fulfillment_status" => $this->mapFulfillmentStatus($status),
            ]
        ];

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
            'Content-Type' => 'application/json'
        ])->put("https://{$this->shop}/admin/api/2025-01/orders/{$shopifyOrderId}.json", $payload);

        if ($response->successful()) {
            Log::info("Shopify order #{$shopifyOrderId} updated successfully.");
        } else {
            Log::error("Failed to update Shopify order #{$shopifyOrderId}: " . json_encode($response->json()));
        }
    }

    private function mapFinancialStatus($status)
    {
        return match ($status) {
            'paid' => 'paid',
            'pending' => 'pending',
            'refunded' => 'refunded',
            'cancelled' => 'voided',
            default => 'pending',
        };
    }

    private function mapFulfillmentStatus($status)
    {
        return match ($status) {
            'shipped' => 'fulfilled',
            'delivered' => 'fulfilled',
            'cancelled' => 'unfulfilled',
            default => 'unfulfilled',
        };
    }
}
