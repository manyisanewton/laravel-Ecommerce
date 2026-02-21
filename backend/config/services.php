<?php

return [
    'mpesa' => [
        'base_url' => env('MPESA_BASE_URL'),
        'consumer_key' => env('MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'short_code' => env('MPESA_SHORT_CODE'),
        'passkey' => env('MPESA_PASSKEY'),
        'callback_url' => env('MPESA_CALLBACK_URL'),
    ],

    'flutterwave' => [
        'base_url' => env('FLUTTERWAVE_BASE_URL'),
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'webhook_hash' => env('FLUTTERWAVE_WEBHOOK_HASH'),
    ],

    'pesapal' => [
        'base_url' => env('PESAPAL_BASE_URL'),
        'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
        'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
    ],
];
