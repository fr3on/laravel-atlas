<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Atlas Dashboard Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the Atlas dashboard will be accessible.
    |
    */

    'path' => env('ATLAS_PATH', 'atlas'),

    /*
    |--------------------------------------------------------------------------
    | Atlas Enabled
    |--------------------------------------------------------------------------
    |
    | This value determines whether the Atlas dashboard is enabled. By default
    | it is only enabled in local environments.
    |
    */

    'enabled' => env('ATLAS_ENABLED', app()->isLocal()),

    /*
    |--------------------------------------------------------------------------
    | Atlas Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Atlas route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware.
    |
    */

    'middleware' => [
        'web',
        // \Fr3on\Atlas\Http\Middleware\Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | List of panels to display in the dashboard.
    |
    */

    'panels' => [
        'routes' => true,
        'models' => true,
        'commands' => true,
        'migrations' => true,
        'events' => true,
        'schedule' => true,
        'config' => true,
        'policies' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    |
    | Configuration for how data is filtered in the dashboard.
    |
    */

    'filters' => [
        'hide_framework_commands' => true,
        'hide_vendor_routes' => false,
    ],
];
