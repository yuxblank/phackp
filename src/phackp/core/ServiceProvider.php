<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/12/2016
 * Time: 15:50
 */

namespace yuxblank\phackp\core;


use yuxblank\phackp\api\Service;

abstract class ServiceProvider implements Service
{
    public $reflectionClass;

    public function __construct()
    {
        $this->reflectionClass = new \ReflectionClass($this);
    }


    public function invoke(callable $method, $params=null)
    {
        if ($this->reflectionClass->hasMethod($method)){
            call_user_func($method, $params);
        }
    }

}