<?php
namespace yuxblank\phackp\providers;

use yuxblank\phackp\api\ErrorHandlerReporter;

class RestErrorHandlerReporter implements ErrorHandlerReporter
{
    public function report(array $throwable)
    {
        return json_encode($throwable);
    }



}
