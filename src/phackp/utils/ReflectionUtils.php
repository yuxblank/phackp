<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/04/2016
 * Time: 15:46
 */

namespace yuxblank\phackp\utils;


use yuxblank\phackp\core\Controller;
use yuxblank\phackp\exceptions\InvocationException;

class ReflectionUtils
{


    /**
     * Return the properties of a given object class as array
     * @param $object
     * @return array
     */
    public static function getProperties($object):array {
        if (is_object($object)){
            $object = get_class($object);
        }
        return array_keys(get_class_vars($object));
    }

    /**
     * Return the properties names of a given object instance
     * @param $object
     * @return array
     */
    public static function getInstanceProperties($object):array {
        return array_keys(get_object_vars($object));
    }
    /**
     * Return the properties values of a given object instance
     * @param $object
     * @return array
     */
    public static function getInstancePropertiesValues($object):array {
        return array_values(get_object_vars($object));
    }


    public static function getDeferredInstanceProperties($object) {
        $array = array();
        foreach (get_object_vars($object) as $key => $var) {
            if ($var!==null) {
                $array[$key] = $var;
            }
        }
        return $array;
    }

    /**
     * @param $className
     * @return mixed
     * @throws InvocationException
     */
    public static function makeInstance($className){
        $instance = new $className();
        if ($instance == null){
            throw new InvocationException($className . " not found in the classpath, please check router configuration.");
        }

        return $instance;
    }


    /**
     * Strip namespaces from a classname, return the global class name.
     * @param $classname
     * @return string
     */
    public static function stripNamespace($classname) {
        return substr($classname, strrpos($classname, '\\') + 1);
    }

    public static function invoke($object, string $action) {
        if (!method_exists($object,$action)){
            throw new InvocationException('Method '.$action .' not found for' . get_class($object));
        }
        $object->$action();
    }

    public static function extendsController ($object){
        return is_subclass_of($object,Controller::class);
    }


}