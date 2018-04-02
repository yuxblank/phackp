<?php

namespace test\tools;

use yuxblank\phackp\core\api\AbstractModule;

class TestModule extends AbstractModule
{

    protected $routes =
        [
            "GET" => [
                [
                    'url' => '/module/test',
                    'class' => \stdClass::class,
                    'method' => 'test',
                    'alias' => 'module.test'
                ]
            ]
        ];

    protected $entityPaths = [

    ];

    public static function install()
    {
        // TODO: Implement install() method.
    }

    public static function uninstall()
    {
        // TODO: Implement uninstall() method.
    }


}