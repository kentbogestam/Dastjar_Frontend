<?php

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
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_PUB_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

    'facebook' => [
        'client_id' => '386281591862936',
        'client_secret' => 'c15e749bfa80839a8d307ce6e172c340',
        'redirect' => env('APP_URL').'login/facebook/callback',
    ],

    'google' => [
        'client_id' => '749840208808-m5f4l2c2128ur05rv25q7u1chpb9nj9p.apps.googleusercontent.com',
        'client_secret' => 'RkWiBcFT8KUAANO9_71wY7KW',
        'redirect' => env('APP_URL').'login/google/callback',
    ],

];
