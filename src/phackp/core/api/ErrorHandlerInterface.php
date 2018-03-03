<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 02/03/18
 * Time: 22.04
 */

namespace yuxblank\phackp\core\api;

interface ErrorHandlerInterface
{
    public function errorHandler();

    public function exceptionHandler(\Throwable $exception);
}