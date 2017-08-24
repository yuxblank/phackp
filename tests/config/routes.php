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
            [
                'url' => '/supa/dupa/{param1}/and/{param2}/{param3}/supa/dupa/{param4}/{param5}',
                'method' => 'supaDupaPathParams',
                'class' => test\controller\App::class,
                'alias' => 'test.supadupa!'
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