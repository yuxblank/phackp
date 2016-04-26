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


    /**
     * Return the properties of a given object class as array
     * @param $object
     * @return array
     */
    public static function getProperties($object):array {
        return array_keys(get_class_vars($object));
    }

    /**
     * Return the properties names of a given object instance
     * @param $object
     * @return array
     */
    public static function getPropertiesWithValues($object):array {
        return array_keys(get_object_vars($object));
    }
    /**
     * Return the properties values of a given object instance
     * @param $object
     * @return array
     */
    public static function getPropertiesValues($object):array {
        return array_values(get_object_vars($object));
    }

    /**
     * Strip namespaces from a classname, return the global class name.
     * @param $classname
     * @return string
     */
    public static function stripNamespace($classname) {
        return substr($classname, strrpos($classname, '\\') + 1);
    }


}