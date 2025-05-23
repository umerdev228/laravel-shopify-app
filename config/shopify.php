<?php

return [
    'api_key'    => env('SHOPIFY_API_KEY'),
    'store_front_api_access_key'    => env('SHOPIFY_STORE_FRONT_API_ACCESS_KEY'),
    'api_secret' => env('SHOPIFY_API_SECRET'),
    'scopes'     => 'read_orders,read_customers',
    'redirect_uri' => env('SHOPIFY_REDIRECT_URI'),
    'access_token' => env('SHOPIFY_ACCESS_TOKEN'),
    'shop_url' => env('SHOPIFY_SHOP_URL'),
    'shop' => env('SHOPIFY_APP_NAME'),
];
