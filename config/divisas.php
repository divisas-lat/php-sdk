<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Divisas.lat API Key
    |--------------------------------------------------------------------------
    |
    | API Key to access Divisas.lat. Get one at https://divisas.lat.
    | Unauthenticated access has strict rate limits.
    |
    */
    'api_key' => env('DIVISAS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | The SDK can cache identical API requests to improve performance and avoid
    | hitting rate limits. Specify the Laravel cache store you want to use,
    | or set it to null to disable caching entirely.
    |
    */
    'cache_store' => env('DIVISAS_CACHE_STORE', null), // e.g. 'redis', 'file'

    /*
    |--------------------------------------------------------------------------
    | Cache Time-To-Live
    |--------------------------------------------------------------------------
    |
    | How long (in seconds) the API responses should be cached locally.
    | Default is 1 hour (3600 seconds).
    |
    */
    'cache_ttl' => env('DIVISAS_CACHE_TTL', 3600),
];
