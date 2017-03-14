<?php
namespace yuxblank\phackp\services\api;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 23:56
 */
/**
 * Interface ThrowableHandler
 * Provide the interface to implements ThrowableHandlers.
 * Those classes will override PHP handlers (error or/and exceptions) with custom delegate Handlers
 * @package yuxblank\phackp\services\api
 */
interface ThrowableHandler extends Provider
{
    /**
     * Add an exception to the custom stack
     * @param \Throwable $throwable
     * @return mixed
     */
    public function handle(\Throwable $throwable);
   /* public function errorDelegate(ErrorHandlerReporter $errorHandlerReporter);*/

    /**
     * Exclude exceptions from the custom stack
     * @param \Throwable $throwable
     * @return mixed
     */
    public function exclude(\Throwable $throwable);
/*    public function errorHandler(int $errno, string $errstr, $errfile, $errline);*/
    /**
     * The function that override PHP Exception handler
     * ex.
     * set_exception_handler(array($this, 'exceptionHandler'));
     * @param \Throwable $throwable
     * @return mixed
     */
    public function exceptionHandler(\Throwable $throwable);
}