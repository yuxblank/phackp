<?php

return [
    'doctrine.config' => [
        'entities_paths' => [defined("APP_ROOT") ? APP_ROOT : "../" . "/doctrine/model"],
        'is_dev' => true,
        'proxy_dir' => null,
        'cache' => null,
        'simple_annotations' => false,
        'connection' =>
            [
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'phackptest',
            ]
    ]
];