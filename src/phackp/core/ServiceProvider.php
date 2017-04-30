<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/12/2016
 * Time: 15:50
 */

namespace yuxblank\phackp\core;


use DI\Container;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\services\api\Provider;
use yuxblank\phackp\services\api\Service;
use yuxblank\phackp\services\exceptions\ServiceProviderException;

/**
 * Class ServiceProvider
 * is the superclass of any Provider of pHackp framework.
 * Those services allow to access DI container after has been created.
 * @package yuxblank\phackp\core
 */

abstract class ServiceProvider implements Service
{
    /** @var  array */
    protected $config;
    /**
     * @Inject
     * @var Container
     */
    protected $container;

    /**
     * ServiceProvider constructor.
     * @throws ServiceProviderException
     */
    public function __construct(array $config)
    {

        // set default provider config
        $this->config = $this->invoke("defaultConfig");

        // replace the default with configured values
        if ($config){
            $this->config = array_replace($this->config, $config);
        }


        if (!class_implements($this,Provider::class)){
            throw new ServiceProviderException("The ServiceProvider " . get_class($this). " does not implements " . Provider::class, ServiceProviderException::NOT_A_PROVIDER);
        }

        try {
            if (!$this->invoke("isValidConfig")) {
                throw new ServiceProviderException('The configuration is not valid for service '
                    . get_class($this),  ServiceProviderException::INVALID_CONFIG);
            }
        } catch (InvocationException $ex){
            throw new InvocationException("The provider does not implements isValidConfig method", InvocationException::SERVICE, $ex);
        }

    }




    /*    public final function bootstrap()
        {
            try {
                if (!$this->invoke("isValidConfig")) {
                    throw new ServiceProviderException('The configuration is not valid for service '
                        . $this->reflectionClass->getName(), ServiceProviderException::INVALID_CONFIG);
                }
            } catch (InvocationException $ex){
                throw new InvocationException("The provider does not implements isValidConfig method", InvocationException::SERVICE, $ex);
            }
        }*/


    /**
     * @param string $name of the param
     * @return array
     */
    public final function getConfig(string $name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }


    /**
     * @Deprecated
     * @param string $method
     * @param null $params
     * @return mixed
     */
    public final function invoke(string $method, $params=null)
    {
        if (method_exists($this, $method)){
            return $this->{$method}($params);
        }
    }





}