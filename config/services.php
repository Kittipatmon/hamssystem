<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Telegram notifications (notification-channels/telegram)
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        // Default chat to receive system notifications (group or user id)
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

    // Also support the package's conventional key name
    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
    ],

    // Microsoft Azure (Outlook/Microsoft 365) OAuth via SocialiteProviders
    'azure' => [
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'tenant' => env('AZURE_TENANT_ID', 'common'),
        'redirect' => env('AZURE_REDIRECT_URI', env('APP_URL') . '/auth/microsoft/callback'),
    ],

];
