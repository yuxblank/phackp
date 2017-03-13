<?php
namespace yuxblank\phackp\services\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:05
 */




interface ExceptionHandler
{
    /**
     * This method is fired if exception occur during execution
     * @param array $throwable
     * @return mixed
     */
    public function onException(array $throwable);

}