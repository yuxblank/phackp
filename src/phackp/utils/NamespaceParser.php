<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 11/04/2016
 * Time: 19:51
 */

namespace yuxblank\phackp\utils;


class NamespaceParser
{


    public static function stripNamespace($classname) {
        return substr($classname, strrpos($classname, '\\') + 1);
    }

}