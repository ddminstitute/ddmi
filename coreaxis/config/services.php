<?php

return [
    'postmark' => ['key' => env('POSTMARK_API_KEY')],
    'resend'   => ['key' => env('RESEND_API_KEY')],
    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    // SMS notification gateway
    // Set SMS_GATEWAY=msg91 or SMS_GATEWAY=fast2sms in .env to activate
    'sms' => [
        'gateway'       => env('SMS_GATEWAY', 'log'),
        'msg91_key'     => env('MSG91_API_KEY'),
        'msg91_sender'  => env('MSG91_SENDER_ID', 'CORXFS'),
        'fast2sms_key'  => env('FAST2SMS_API_KEY'),
    ],
];
