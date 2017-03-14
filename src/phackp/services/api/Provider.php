<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/03/2017
 * Time: 20:29
 */

namespace yuxblank\phackp\services\api;
/**
 * Interface Provider
 * Interface required by any ServiceProvider implementation
 * @package yuxblank\phackp\services\api
 */
interface Provider extends Service
{

    /**
     * Define default configuration of the provider.
     * Must be an array.
     * If the user does not provide a config on ServiceProvider registration, this will be used as config. (must pass isValidConfig!)
     * @return array
     */
    public function defaultConfig():array;

    /**
     * Invoked right after constructor, here you can setup the Provider status based on configurations
     * @return mixed
     */
    public function bootstrap();

    /**
     * Define when a configuration is valid for the Provider.
     * Since providers are configurable, it's responsibility of the developer to diagnose bad configurations.
     * Return true if the configuration passed satisfy requirements.
     * If false, ServiceProviderException is thrown by ServiceProvider
     * @return mixed
     */
    public function isValidConfig():bool;



}