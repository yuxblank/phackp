<?php
namespace yuxblank\phackp\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:05
 */




interface ErrorHandler
{
    /**
     * Those method are fired when and error is thrown
     * @param array $throwable
     * @return mixed
     */
    public function fatal(array $throwable);
    public function warning(array $throwable);
    public function notice(array $throwable);
    public function unknown(array $throwable);

}