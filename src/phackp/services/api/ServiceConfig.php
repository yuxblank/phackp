<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuriblanc
 * Date: 13/03/17
 * Time: 17:54
 */

namespace yuxblank\phackp\services\api;


interface ServiceConfig
{

    /**
     * Define a valid configuration for the service
     * @return bool
     */
    public function isValid():bool;
    public function config(array $config);
    public function getConfig():array;
    public function getParam(string $key);
    public function getDefaults():array;

}