<?php
namespace yuxblank\phackp\services;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:08
 */
class HtmlErrorHandlerReporter implements \yuxblank\phackp\api\ErrorHandlerReporter
{
    public function report(array $throwable)
    {
        foreach ($throwable as $ex){
            echo $ex->getMessage();
        }
    }


}