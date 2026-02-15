<?php

use Illuminate\Support\Str;

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://gameverse-app.vercel.app'],
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];
