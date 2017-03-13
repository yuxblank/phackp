<?php
namespace yuxblank\phackp\api;
use yuxblank\phackp\services\api\ServiceConfig;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:08
 */




interface Service
{
    public function config(ServiceConfig $config);
    public function invoke(string $method, $params=null);

}