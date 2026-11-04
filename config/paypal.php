<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal API credentials
    |--------------------------------------------------------------------------
    */
    'mode'    => env('PAYPAL_MODE', 'sandbox'),  // 'sandbox' or 'live'
    'sandbox' => [
        'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'app_id'        => '',
    ],
    'live' => [
        'client_id'     => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'app_id'        => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | SDK configuration
    |--------------------------------------------------------------------------
    */
    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'),  // or 'Authorization','Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''),  // IPN url if you need
    'locale'         => env('PAYPAL_LOCALE', 'en_US'),
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true),
];
