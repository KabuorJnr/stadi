<?php

return [
    'env' => env('MPESA_ENV', 'sandbox'),
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'shortcode' => env('MPESA_SHORTCODE', '174379'),
    'passkey' => env('MPESA_PASSKEY'),
    'callback_url' => env('MPESA_CALLBACK_URL'),
    'stk_callback_url' => env('MPESA_STK_CALLBACK_URL'),
    'c2b_confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL'),
    'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL'),
    'base_url' => [
        'sandbox' => 'https://sandbox.safaricom.co.ke',
        'production' => 'https://api.safaricom.co.ke',
    ],
    'endpoints' => [
        'oauth_token' => '/oauth/v1/generate?grant_type=client_credentials',
        'stk_push' => '/mpesa/stkpush/v1/processrequest',
        'stk_query' => '/mpesa/stkpushquery/v1/query',
        'c2b_register' => '/mpesa/c2b/v1/registerurl',
    ],
];
