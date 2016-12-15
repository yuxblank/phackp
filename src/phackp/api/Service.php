<?php
namespace yuxblank\phackp\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:08
 */




interface Service
{
    public function invoke(string $method, $params=null);

}