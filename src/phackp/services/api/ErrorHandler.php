<?php
namespace yuxblank\phackp\services\api;
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:05
 */

/**
 * Interface ErrorHandler
 * Required to implement an error HandlerProvider delegate
 * @package yuxblank\phackp\services\api
 */
interface ErrorHandler
{

    /**
     * Fired on fatal error
     * @param array $throwable
     * @return mixed
     */
    public function fatal(array $throwable);
    /**
     * Fired on warning
     * @param array $throwable
     * @return mixed
     */
    public function warning(array $throwable);
    /**
     * Fired on notice messages
     * @param array $throwable
     * @return mixed
     */
    public function notice(array $throwable);
    /**
     * Fired on fatal|unknown error
     * @param array $throwable
     * @return mixed
     */
    public function unknown(array $throwable);

}