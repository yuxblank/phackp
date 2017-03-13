<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/12/2016
 * Time: 15:50
 */

namespace yuxblank\phackp\core;



use yuxblank\phackp\services\api\Service;
use yuxblank\phackp\services\api\ServiceConfig;
use yuxblank\phackp\services\exceptions\ServiceProviderException;

class ServiceProvider implements Service
{
    protected $reflectionClass;
    protected $serviceConfig;

    public function __construct()
    {
        $this->reflectionClass = new \ReflectionClass($this);
    }

    /**
     * Set ServiceConfig impl. as default serviceConfig.
     * If ServiceConfig->isValid() is false, throw ServiceProviderException
     * @param ServiceConfig $config
     * @throws ServiceProviderException
     */
    public function config(ServiceConfig $config)
    {
        if (!$config->isValid()){
            throw new ServiceProviderException('The configuration is not valid for service '
                . $this->reflectionClass->getName(), ServiceProviderException::INVALID_CONFIG);
        }
        $this->serviceConfig = $config;

    }

    public function invoke(string $method, $params=null)
    {
        if ($this->reflectionClass->hasMethod($method)){
            return $this->{$method}($params);
        }
    }


}