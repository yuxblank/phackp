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
        // TODO: Implement isValid() method.
    }

    public function config(array $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->getConfig();
    }


    public function getParam(string $key)
    {
        return $this->config[$key];
    }

    public function getDefaults(): array
    {
        return [
            'exception_handler_enable' => true,
            'exception_handler_delegate' => PhackpExceptionHandler::class,
        ];
    }


}