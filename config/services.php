<?php


use App\Helpers\Common;
    

return [
    
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID','448519939019-8a57qj9lgov1qka13767edau88r2tr04.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET','OMM1yEKVuszFSvA2lyBthB34'),
        'redirect' => env('GOOGLE_CALLBACK_URL',  'https://usecaravan.com/login/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID','659633381120429'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET','b0add68fadedfd00aef94654af07d797'),        
        'redirect' => env('FACEBOOK_CALLBACK_URL', 'https://usecaravan.com/login/facebook/callback' ),
    ],
];
