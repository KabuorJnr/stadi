<?php

return [
    'driver' => env('SMS_DRIVER', 'africastalking'),
    'africastalking' => [
        'api_key' => env('AT_API_KEY'),
        'username' => env('AT_USERNAME', 'sandbox'),
        'sender_id' => env('AT_SENDER_ID', 'Stadi'),
        'base_url' => 'https://api.africastalking.com/version1/messaging',
        'sandbox_url' => 'https://api.sandbox.africastalking.com/version1/messaging',
    ],
];
