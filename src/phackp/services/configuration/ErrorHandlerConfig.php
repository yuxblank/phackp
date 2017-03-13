<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuriblanc
 * Date: 13/03/17
 * Time: 17:59
 */

namespace yuxblank\phackp\services\configuration;


use yuxblank\phackp\providers\PhackpExceptionHandler;
use yuxblank\phackp\services\api\ServiceConfig;

class ErrorHandlerConfig implements ServiceConfig
{
    protected $config;

    public function isValid(): bool
    {
        return true;
    }

    public function config(array $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }


    public function getParam(string $key)
    {
        return $this->config[$key];
    }

    public function getDefaults(): array
    {

    }


}