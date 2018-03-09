<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 04/03/18
 * Time: 0.33
 */

namespace yuxblank\phackp\core\api;


interface Module
{

    public static function install();
    public static function uninstall();
    public function getRoutes():array;
}