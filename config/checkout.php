<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checkout Success URL
    |--------------------------------------------------------------------------
    |
    | The URL Stripe redirects to after successful payment.
    | {CHECKOUT_SESSION_ID} is replaced by Stripe with the actual session ID.
    |
    */
    'success_url' => env('FRONTEND_URL') . '/checkout/success',

    /*
    |--------------------------------------------------------------------------
    | Checkout Cancel URL
    |--------------------------------------------------------------------------
    |
    | The URL Stripe redirects to when the customer cancels payment.
    |
    */
    'cancel_url' => env('FRONTEND_URL') . '/checkout/cancel',

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The currency used for Stripe Checkout Sessions.
    | Falls back to the Cashier currency configuration.
    |
    */
    'currency' => 'usd',

    'webhook_secret' => env('CHECKOUT_WEBHOOK_SECRET'),
];
