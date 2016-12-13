<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:56
 */

namespace yuxblank\phackp\api;


interface ThrowableHandler
{
    public function handle(\Throwable $throwable);
    public function delegate(ErrorHandlerReporter $errorHandlerReporter);
    public function exclude(\Throwable $throwable);
}