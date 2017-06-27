<?php
return [

    /**
     * New routes using PHP arrays
     */

    'routes' => [

        'GET' => [
            [
                'url' => '/',
                'alias' => 'home',
                'method' => 'index',
                'class' => test\controller\App::class
            ],
            [
                'url' => 'blog/title/{id}',
                'method' => 'showPost',
                'alias' => 'blogpost',
                'class' => test\controller\App::class
            ],
            [
                'url' => 'blog/{id}',
                'method' => 'showPost',
                'class' => test\controller\App::class
            ],

            [
                'url' => 'tag/{id}',
                'action' => 'tagSearch',
                'class' => test\controller\App::class
            ],

            [
                'url' => 'api',
                'action' => 'Rest@get'
            ],

        ],
        'POST' => [
            [
                'url' => 'api',
                'action' => 'Rest@post',
                'options' =>
                    [
                        'accept' => 'application/json',
                        'return' => 'application/json'
                    ]
            ],
        ],
        'PUT' => [
            [
                'url' => 'api',
                'action' => 'Rest@put'
            ],
        ],
        'PATCH' => [
            [
                'url' => 'api',
                'action' => 'Rest@patch'
            ]
        ],
        'DELETE' => [
            [
                'url' => 'api',
                'action' => 'Rest@delete'
            ]
        ],
        'HEAD' => [
            [
                'url' => 'api',
                'action' => 'Rest@head'
            ]
        ],
        'OPTIONS' => [
            [
                'url' => 'api',
                'action' => 'Rest@options'
            ]
        ],

        /**
         * ERROR is not HTTP. Is used for pHackp error page mapping.
         */

        'ERROR' => [
            404 =>
                [
                    'url' => '404',
                    'action' => 'Error@notFound404'
                ],
        ]
    ]


];