<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // এখানে sanctum/csrf-cookie এবং login/logout থাকতে হবে
    'paths' => ['api/*', 'sanctum/csrf-cookie', '_ignition/*', 'login', 'logout'],

    'allowed_methods' => ['*'],

    // তোমার React অ্যাপের URL এখানে দাও
    'allowed_origins' => ['http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // এটি অবশ্যই true হতে হবে, নাহলে কুকি কাজ করবে না
    'supports_credentials' => true,
];
