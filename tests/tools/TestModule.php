<?php

class TestModule implements \yuxblank\phackp\core\Module
{
    public static function install()
    {
        // TODO: Implement install() method.
    }

    public static function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    public function getRoutes(): array
    {
        return ["GET" => [
            [
                'url' => '/module/test',
                'class' => \stdClass::class,
                'method' => 'test',
                'alias' => 'module.test'
            ]
        ]];
    }


}