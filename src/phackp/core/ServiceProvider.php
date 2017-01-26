<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/12/2016
 * Time: 15:50
 */

namespace yuxblank\phackp\core;


use yuxblank\phackp\api\Service;

class ServiceProvider implements Service
{
    protected $reflectionClass;

    public function __construct()
    {
        $this->reflectionClass = new \ReflectionClass($this);
    }


    public function invoke(string $method, $params=null)
    {
        if ($this->reflectionClass->hasMethod($method)){
            return $this->{$method}($params);
        }
    }


}