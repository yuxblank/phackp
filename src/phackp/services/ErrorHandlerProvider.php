<?php
namespace yuxblank\phackp\services;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 22:52
 */

class ErrorHandlerProvider implements \yuxblank\phackp\api\ThrowableHandler
{
    protected $exceptions = [];
    protected $excluded = [];

    public function handle(\Throwable $throwable)
    {
        $this->exceptions[] = $throwable;
    }

    public function delegate(\yuxblank\phackp\api\ErrorHandlerReporter $errorHandlerReporter)
    {
        // todo filter exceptions
        $errorHandlerReporter->report($this->exceptions);

    }

    public function exclude(\Throwable $throwable)
    {
        $this->excluded[] = $throwable;
    }


}