<?php
namespace yuxblank\phackp\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:05
 */




interface ErrorHandlerReporter
{
    public function report(array $throwable);

}