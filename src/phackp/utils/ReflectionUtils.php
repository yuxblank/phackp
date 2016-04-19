<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/04/2016
 * Time: 15:46
 */

namespace yuxblank\phackp\utils;


class ReflectionUtils
{


    public static function getProperties($object):array {
        return array_keys(get_class_vars($object));
    }
    public static function stripNamespace($classname) {
        return substr($classname, strrpos($classname, '\\') + 1);
    }


}