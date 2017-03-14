<?php
namespace yuxblank\phackp\services\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:05
 */
/**
 * Interface ExceptionHandler
 * Required to create a custom exception HandlerProvider delegate
 * @package yuxblank\phackp\services\api
 */
interface ExceptionHandler
{
    /**
     * This method is fired if an uncaught exception is thrown occur during execution
     * @param array $throwable
     * @return mixed
     */
    public function onException(array $throwable);

}