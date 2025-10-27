<?php

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'), // 'live' nếu chạy thật

    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'app_id' => '',
    ],

    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'app_id' => '',
    ],

    'payment_action' => 'Sale',
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url' => '',
    'locale' => 'en_US',
];
