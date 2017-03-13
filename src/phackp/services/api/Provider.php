<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/03/2017
 * Time: 20:29
 */

namespace yuxblank\phackp\services\api;


interface Provider extends Service
{

    /**
     * Define default ServiceConfig instance
     * @return ServiceConfig
     */
    public function defaultConfig():array;

    /**
     * Invoked right after constructor, here you can setup the Provider status based on configurations
     * @return mixed
     */
    public function setup();


    public function isValidConfig();

}