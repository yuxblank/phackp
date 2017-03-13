<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/12/2016
 * Time: 15:50
 */

namespace yuxblank\phackp\core;



use yuxblank\phackp\services\api\Provider;
use yuxblank\phackp\services\api\Service;
use yuxblank\phackp\services\api\ServiceConfig;
use yuxblank\phackp\services\exceptions\ServiceProviderException;

class ServiceProvider implements Service
{
    protected $reflectionClass;
    /** @var  ServiceConfig */
    protected $config;

    /**
     * ServiceProvider constructor.
     * @throws ServiceProviderException
     */
    public function __construct()
    {
        $this->reflectionClass = new \ReflectionClass($this);
        if (!$this->reflectionClass->implementsInterface(Provider::class)){
            throw new ServiceProviderException("The ServiceProvider " . $this->reflectionClass->getName() . " does not implements " . Provider::class, ServiceProviderException::NOT_A_PROVIDER);
        }

    }


    public final function bootstrap()
    {

        if (!$this->config){
            $this->config = $this->invoke("defaultConfig");
        }

        if (!$this->config->getConfig()){
            $this->config->config($this->config->getDefaults());
        }

        if (!$this->config->isValid()){
            throw new ServiceProviderException('The configuration is not valid for service '
                . $this->reflectionClass->getName(), ServiceProviderException::INVALID_CONFIG);
        }
    }


    public function config(ServiceConfig $config)
    {
        $this->config =  $config;
    }

    public final function invoke(string $method, $params=null)
    {
        if ($this->reflectionClass->hasMethod($method)){
            return $this->{$method}($params);
        }
    }





}