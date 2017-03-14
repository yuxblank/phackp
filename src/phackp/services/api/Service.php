<?php
namespace yuxblank\phackp\services\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:08
 */
/**
 * Interface Service
 * Used by ServiceProvider SuperClass.
 * @package yuxblank\phackp\services\api
 */
interface Service
{
    /**
     * Must provide the ability to invoke methods on self and subclasses
     * @param string $method
     * @param null $params
     * @return mixed
     */
    public function invoke(string $method, $params=null);


}