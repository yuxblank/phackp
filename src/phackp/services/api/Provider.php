<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/03/2017
 * Time: 20:29
 */

namespace yuxblank\phackp\services\api;


interface Provider
{
    public function defaultConfig():array;
    /**
     * Set ServiceConfig impl. as default serviceConfig.
     * If ServiceConfig->isValid() is false, throw ServiceProviderException
     * @param ServiceConfig $config
     */

}