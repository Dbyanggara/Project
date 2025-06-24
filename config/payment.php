<?php

return [
    'dana' => [
        'merchant_id' => env('DANA_MERCHANT_ID'),
        'client_id' => env('DANA_CLIENT_ID'),
        'client_secret' => env('DANA_CLIENT_SECRET'),
        'environment' => env('DANA_ENVIRONMENT', 'sandbox'), // sandbox atau production
        'base_url' => env('DANA_ENVIRONMENT') === 'production'
            ? 'https://api.dana.id'
            : 'https://api-sandbox.dana.id',
        'webhook_secret' => env('DANA_WEBHOOK_SECRET'),
    ],

    'gopay' => [
        'merchant_id' => env('GOPAY_MERCHANT_ID'),
        'client_id' => env('GOPAY_CLIENT_ID'),
        'client_secret' => env('GOPAY_CLIENT_SECRET'),
        'environment' => env('GOPAY_ENVIRONMENT', 'sandbox'),
        'base_url' => env('GOPAY_ENVIRONMENT') === 'production'
            ? 'https://api.gojek.com'
            : 'https://api-sandbox.gojek.com',
    ],

    'ovo' => [
        'merchant_id' => env('OVO_MERCHANT_ID'),
        'client_id' => env('OVO_CLIENT_ID'),
        'client_secret' => env('OVO_CLIENT_SECRET'),
        'environment' => env('OVO_ENVIRONMENT', 'sandbox'),
        'base_url' => env('OVO_ENVIRONMENT') === 'production'
            ? 'https://api.ovo.id'
            : 'https://api-sandbox.ovo.id',
    ],
];
