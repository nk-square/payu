<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment form view
    |--------------------------------------------------------------------------
    |
    | Load the payment form from this path. Replace with your own view and path
    | if required
    |
    */
    'payment_form' => 'payu::payment-form',

    /*
    |--------------------------------------------------------------------------
    | Default payu account
    |--------------------------------------------------------------------------
    |
    | The default payu account to use from accounts
    |
    */
    'default' => 'money',

    /*
    |--------------------------------------------------------------------------
    | Testing or live mode
    |--------------------------------------------------------------------------
    |
    | The values can be 'money' for payumoney, 'biz' for payubiz, 'local' for local or false for live mode
    |
    */
    'testing' => env('PAYU_TESTING',false),

    /*
    |--------------------------------------------------------------------------
    | Payu accounts
    |--------------------------------------------------------------------------
    |
    | The payu accounts go here. The following fields are required key, salt, type - money or biz
    | and optionally auth_header for payumoney
    |
    */
    'accounts' => [
        'money' => [
            'key' => env('PAYU_KEY'),
            'salt' => env('PAYU_SALT'),
            'type' => env('PAYU_TYPE'),
            'auth_header' => env('PAYU_AUTH_HEADER')
        ],
        // 'biz' => [
        //     'key' => env('PAYU_KEY'),
        //     'salt' => env('PAYU_SALT'),
        //     'type' => env('PAYU_TYPE'),
        // ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox credentials
    |--------------------------------------------------------------------------
    |
    | The sandbox credentials for payumoney and payubiz
    |
    */
    'sandbox' => [
        'money' => [
            'key' => env('PAYU_KEY'),
            'salt' => env('PAYU_SALT'),
            'auth_header' => env('PAYU_AUTH_HEADER'),
        ],
        'biz' => [
            'key' => 'gtKFFx',
            'salt' => 'eCwWELxi',
        ]
    ],
];
