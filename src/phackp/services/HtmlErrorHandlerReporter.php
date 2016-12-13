<?php
namespace yuxblank\phackp\services;
use yuxblank\phackp\api\ErrorHandlerReporter;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:08
 */
class HtmlErrorHandlerReporter implements ErrorHandlerReporter
{
    public function report(array $throwable)
    {
        foreach ($throwable as $ex){
            echo $ex->getMessage();
        }
    }


}