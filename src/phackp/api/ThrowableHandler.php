<?php
namespace yuxblank\phackp\api;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:56
 */




interface ThrowableHandler
{
    public function handle(\Throwable $throwable);
    public function delegate(ErrorHandlerReporter $errorHandlerReporter);
    public function exclude(\Throwable $throwable);
}