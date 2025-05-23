<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShopifyAuthController extends Controller
{
    public function redirectToShopify()
    {
        $shop = config('shopify.shop');
        $redirectUri = url(config('shopify.redirect_uri'));
        $apiKey = config('shopify.api_key');

        $url = "https://{$shop}.myshopify.com/admin/oauth/authorize?client_id={$apiKey}&scope=read_orders,read_customers&redirect_uri={$redirectUri}";

        return redirect()->away($url);
    }

//    public function redirectToShopify()
//    {
//        $shopifyUrl = "https://accounts.shopify.com/oauth/authorize";
//        $params = [
//            'client_id' => config('shopify.api_key'),
//            'scope' => 'read_orders,read_customers',
//            'redirect_uri' => url(config('shopify.redirect_uri')),
//            'response_type' => 'code'
//        ];
//        return redirect($shopifyUrl . '?' . http_build_query($params));
//    }


//    public function handleShopifyCallback(Request $request)
//    {
//        $shop = $request->query('shop');
//        $code = $request->query('code');
//        $response = Http::post("https://{$shop}/admin/oauth/access_token", [
//            'client_id' => config('shopify.api_key'),
//            'client_secret' => config('shopify.api_secret'),
//            'code' => $code,
//        ]);
//
//        $accessToken = $response->json()['access_token'];
//
//        $customerData = Http::withHeaders([
//            'X-Shopify-Access-Token' => $accessToken
//        ])->get("https://{$shop}/admin/api/2023-07/customers.json")->json();
//
//        $customer = User::updateOrCreate(
//            ['email' => $customerData['customers'][0]['email']],
//            ['name' => $customerData['customers'][0]['first_name']]
//        );
//
//        auth()->login($customer);
//        return redirect('/dashboard');
//    }


    public function handleShopifyCallback(Request $request)
    {
        Storage::disk('local')->put('shopify-callback.txt', json_encode($request->all()));
        $code = $request->query('code');
        Log::info('code: ' . $code);

        if (!$code) {
            return redirect('/login')->with('error', 'Shopify login failed.');
        }
        $shop = config('shopify.shop');

        // Exchange the code for an access token
        $response = Http::asForm()->post("https://{$shop}.myshopify.com/admin/oauth/access_token", [
            'client_id' => config('shopify.api_key'),
            'client_secret' => config('shopify.api_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);
        Log::info('client_id: ' . config('shopify.api_key'));
        Log::info('client_secret: ' . config('shopify.api_secret'));


        $data = $response->json();
        Storage::disk('local')->put('asForm.txt', json_encode(json_decode($response->body())));

        if (!isset($data['access_token'])) {
            return redirect('/login')->with('error', 'Shopify authentication failed.');
        }

        // Fetch customer details using the access token
        $customerResponse = Http::withHeaders([
            'X-Shopify-Access-Token' => $data['access_token'], // Use the correct header for private app authentication
        ])->get("https://{$shop}.myshopify.com/admin/api/2025-01/customers.json");

        $customerData = $customerResponse->json();

        if (isset($customerData['customers'][0])) {
            $customer = $customerData['customers'][0];

            // Check if user already exists
            $user = User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['first_name'] . ' ' . $customer['last_name'],
                    'password' => bcrypt(uniqid()) // Dummy password
                ]
            );

            Auth::login($user);
        }

        return redirect('/customer/orders')->with('success', 'Logged in via Shopify!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out.');
    }
}
