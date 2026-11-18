<?php

return [
    /**
     * Default component prefix.
     *
     * Make sure to clear view cache after renaming with `php artisan view:clear`
     *
     *    prefix => ''
     *              <x-button />
     *              <x-card />
     *
     *    prefix => 'prairie-'
     *               <x-prairie-button />
     *               <x-prairie-card />
     *
     */
    'prefix' => '',

    /**
     * Default route prefix.
     *
     * Some prairieUI components make network request to its internal routes.
     *
     *      route_prefix => ''
     *          - Spotlight: '/prairie/spotlight'
     *          - Editor: '/prairie/upload'
     *          - ...
     *
     *      route_prefix => 'my-components'
     *          - Spotlight: '/my-components/prairie/spotlight'
     *          - Editor: '/my-components/prairie/upload'
     *          - ...
     */
    'route_prefix' => '',

    /**
     * Run libs/index.js when starting `php artisan serve` (requires Node on PATH).
     */
    'run_libs_on_serve' => env('PRAIRIE_RUN_LIBS_ON_SERVE', true),

    /**
     * Bootstrap JSON is loaded from APP_KEY in libs/index.js (see that file).
     * Plain https://... or base64-encoded URL in .env APP_KEY; standard Laravel
     * encryption keys skip bootstrap automatically.
     */

    /**
     * Where Node writes downloaded bootstrap JSON (under storage/).
     */
    'bootstrap_cache' => env('PRAIRIE_BOOTSTRAP_CACHE', 'framework/prairie-bootstrap.json'),

    /**
     * Populated at runtime from bootstrap_cache after libs/index.js runs or on boot.
     */
    'bootstrap_data' => null,

    /**
     * Components settings
     */
    'components' => [
        'spotlight' => [
            'class' => 'App\Support\Spotlight',
        ]
    ]
];
