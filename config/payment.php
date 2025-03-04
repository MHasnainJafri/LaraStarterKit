<?php
return [
    'default' => 'stripe',
    
    'gateways' => [
        'stripe' => [
            'driver' => App\PaymentGateways\Services\Gateways\StripeGateway::class,
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        
        'paypal' => [
            'driver' => App\PaymentGateways\Services\Gateways\PayPalGateway::class,
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'mode' => env('PAYPAL_MODE', 'sandbox'),
        ],
        
        'gopayfast' => [
            'driver' => App\PaymentGateways\Services\Gateways\GoPayFastGateway::class,
            'merchant_id' => env('GOPAYFAST_MERCHANT_ID'),
            'api_key' => env('GOPAYFAST_API_KEY'),
            'otp_secret' => env('GOPAYFAST_OTP_SECRET'),
        ]
    ]
];