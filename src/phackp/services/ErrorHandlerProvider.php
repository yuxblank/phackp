<?php
namespace yuxblank\phackp\services;
use yuxblank\phackp\api\ErrorHandlerReporter;
use yuxblank\phackp\api\ThrowableHandler;
use yuxblank\phackp\core\ServiceProvider;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 22:52
 */

class ErrorHandlerProvider extends ServiceProvider implements ThrowableHandler
{

    const HANDLE = "handle";
    protected $exceptions = [];
    protected $excluded = [];

    public function handle(\Throwable $throwable)
    {
        $this->exceptions[] = $throwable;
    }

    public function delegate(ErrorHandlerReporter $errorHandlerReporter)
    {
        // todo exclude exceptions
        return $errorHandlerReporter->report($this->exceptions);

    }

    public function exclude(\Throwable $throwable)
    {
        $this->excluded[] = $throwable;
    }


}