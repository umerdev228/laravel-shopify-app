<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeCustomerMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShopifyWebhookController extends Controller
{
    public function handleOrderUpdate(Request $request)
    {
        $order = $request->all();
        Log::info('Shopify Order Updated:', $order);
        if (isset($order['customer'])) {
            $customer = User::updateOrCreate(
                ['email' => $order['customer']['email']],
                ['name' => $order['customer']['first_name'] ?? 'Guest']
            );
            $userId = $customer->id;
        } else {
            $userId = 1;
        }

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


        return response()->json(['message' => 'Order updated'], 200);
    }

    public function handleCustomerUpdate(Request $request)
    {
        $data = $request->all();
        Log::info('Shopify Customer Updated:', $data);
        $user = User::where('email', $data['email'])->first();
        if (User::where('email', $data['email'])->exists()) {
            $userId = $user->id;
        }
        else {
            $user = New User();
            $randomPassword = str()->random(12);
            $user->password = Hash::make($randomPassword);
            $user->email = $data['email'];
            $user->name = $data['first_name'] . ' ' . $data['last_name'];
//            $user->phone = $data['phone'] ?? null;
            $user->save();
            // Send email only if user is newly created
            if ($user->wasRecentlyCreated) {
                Mail::to($user->email)->send(new WelcomeCustomerMail($user, $randomPassword));
            }

        }


        return response()->json(['message' => 'Customer updated'], 200);
    }
}
