<?php
namespace yuxblank\phackp\services\api;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:56
 */




interface ThrowableHandler
{
    public function handle(\Throwable $throwable);
   /* public function errorDelegate(ErrorHandlerReporter $errorHandlerReporter);*/
    public function exclude(\Throwable $throwable);
/*    public function errorHandler(int $errno, string $errstr, $errfile, $errline);*/
    public function exceptionHandler(\Throwable $throwable);
}