<?php

use Illuminate\Support\Str;

return [
    'default' => env('CACHE_DRIVER', env('CACHE_STORE', 'file')),

    'stores' => [
        'array' => ['driver' => 'array', 'serialize' => false],
        'file' => ['driver' => 'file', 'path' => storage_path('framework/cache/data')],
        'null' => ['driver' => 'null'],
        'database' => [
            'driver' => 'database',
            'table' => env('CACHE_TABLE', 'cache'),
            'connection' => env('CACHE_DB_CONNECTION'),
            'lock_connection' => env('CACHE_LOCK_DB_CONNECTION'),
        ],
    ],

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),
];
