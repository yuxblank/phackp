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
                'url' => '/blog/title/{id}',
                'method' => 'testGet',
                'alias' => 'test.get',
                'class' => test\controller\App::class
            ],
            [
                'url' => '/blog/{id}',
                'method' => 'showPost',
                'class' => test\controller\App::class
            ],
            [
                'url' => '/tag/{id}',
                'method' => 'tagSearch',
                'class' => test\controller\App::class
            ],
            [
                'url' => '/json/response',
                'method' => 'testJsonResponse',
                'class' => test\controller\App::class
            ],

        ],

    /*    'ERROR' => [
            404 =>
                [
                    'url' => '404',
                    'method' => 'Error@notFound404',
                    'class' => Error::class
                ],
        ]*/
    ]


];