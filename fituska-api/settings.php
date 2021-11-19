<?php

// settings.php

define('APP_ROOT', __DIR__);

return [
    'jwt-key' => 'superstrongkey:peepogiggle:',
    // change this to false if shipping to production
    'displayErrorDetails' => true,
    'determineRouteBeforeAppMiddleware' => false,

    'doctrine' => [
        // if true, metadata caching is forcefully disabled
        'dev_mode' => true,

        // path where the compiled metadata info will be cached
        // make sure the path exists and it is writable
        'cache_dir' => APP_ROOT . '/var/doctrine',

        // you should add any other path containing annotated entity classes
        'metadata_dirs' => [APP_ROOT . '/src/Domain'],

        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => 'mysql',
            // uncomment next line if you are using this outside of docker and fill with valid port
            // 'port' => 6033,
            'dbname' => 'fituska',
            'user' => 'root',
            'password' => 'root',
            'charset' => 'utf8'
        ]
    ]
];

