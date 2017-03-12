<?php
namespace yuxblank\phackp\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:05
 */




interface ExceptionHandlerReporter
{
    /**
     * Define the way to represent execptions
     * @param array $throwable
     * @return mixed
     */
    public function display(array $throwable);

}