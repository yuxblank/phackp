<?php
namespace yuxblank\phackp\providers;

use yuxblank\phackp\api\ErrorHandlerReporter;

class RestErrorHandlerReporter implements ErrorHandlerReporter
{
    public function report(array $throwable)
    {
        $caught = [];
        foreach ($throwable as $ex){
            $caught[get_class($ex)] =  $ex;
        }
        return $caught;

    }



}
