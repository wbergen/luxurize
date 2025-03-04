<?php
    return [
        'provider' => 'paypal',
        'braintree' => [
            'id' => env('BRAINTREE_MERCHANT_ID', ''),
            'token' => env('BRAINTREE_TOKEN', 'PRODUCTION'),
            'pub_key' => env('BRAINTREE_PUB_KEY', 'PRODUCTION'),
            'private_key' => env('BRAINTREE_PRIV_KEY', 'PRODUCTION'),
            'env' => env('BRAINTREE_ENV', 'sandbox')
        ],
        'places' => [
            'api_key' => env('GOOGLE_COULD_API_KEY', '')
        ],
        'paypal' => [
            'api_key' => env('PAYPAL_API_KEY', ''),
            'api_secret' => env('PAYPAL_API_SECRET', '')
        ]
    ];
