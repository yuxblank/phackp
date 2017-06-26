<?php
return [


    'app.globals' =>
        [
            "APP_NAME" => "phackp",
            "APP_VERSION" => "0.1-test",
            "AUTHOR" =>
                [
                    "NAME" => "Name",
                    "EMAIL" => "email@devexample.com"
                ],
            "APP_MODE" => "DEBUG",
            'APP_URL' => 'http://localhost:7000',
        ],

    'app.session' =>
        [
            'LIFETIME' => 1024,
            'USE_COOKIES' => true,
            'NAME' => 'pHackp-session',
            'COOKIE' =>
                [
                    'PATH' => '/',
                    'DOMAIN' => 'http://test-server.com',
                    'SECURE' => array_key_exists('HTTPS', $_SERVER),
                    'HTTP_ONLY' => false
                ]
        ],


    'app.http' => [
        'INJECT_QUERY_STRING' => true,
        "GZIP" => false,
    ],

    'app.view' =>
        [
            'ROOT' => 'src/view',
            'HOOKS' =>
                [
                    'FOOTER' => 'tags/footer.php'
                ]

        ]


];