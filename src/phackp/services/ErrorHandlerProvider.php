<?php
namespace yuxblank\phackp\services;
use yuxblank\phackp\api\ErrorHandlerReporter;
use yuxblank\phackp\api\ExceptionHandlerReporter;
use yuxblank\phackp\api\ThrowableHandler;
use yuxblank\phackp\core\Application;
use yuxblank\phackp\core\ServiceProvider;
use yuxblank\phackp\providers\PhackpErrorReporter;
use yuxblank\phackp\providers\PhackpExceptionReporter;
use yuxblank\phackp\services\exceptions\PhackpRuntimeException;
use yuxblank\phackp\services\exceptions\ServiceProviderException;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 13/12/2016
 * Time: 22:52
 */

class ErrorHandlerProvider extends ServiceProvider implements ThrowableHandler
{

    const HANDLE = "handle";
    protected $exceptions = [];
    protected $excluded = [];
    /** @var  ErrorHandlerReporter */
    protected $errorDelegate;
    /** @var  ExceptionHandlerReporter */
    protected $exceptionDelegate;

    /**
     * ErrorHandlerProvider constructor.
     */
    public function __construct()
    {
        parent::__construct();
        set_error_handler(array($this, 'errorHandler'), E_ALL);
        set_exception_handler(array($this, 'exceptionHandler'));
    }


    public function handle(\Throwable $throwable)
    {
        $this->exceptions[] = $throwable;
    }

    public function errorDelegate(ErrorHandlerReporter $errorHandlerReporter)
    {
        $this->errorDelegate = $errorHandlerReporter;
    }
    public function exceptionDelegate(ExceptionHandlerReporter $exceptionHandlerReporter)
    {
        $this->errorDelegate = $exceptionHandlerReporter;
    }


    public function exclude(\Throwable $throwable)
    {
        $this->excluded[] = $throwable;
    }

    private function getErrorHandler():ErrorHandlerReporter{
        if (!$this->errorDelegate){
            $this->errorDelegate(new PhackpErrorReporter()); // set default
        }
        return $this->errorDelegate;
    }
    private function getExceptionHandler():ExceptionHandlerReporter{
        if (!$this->exceptionDelegate){
            $this->exceptionDelegate(new PhackpExceptionReporter()); // set default
        }
        return $this->exceptionDelegate;
    }


    public function errorHandler(int $errno, string $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }


        switch ($errno) {
            case E_USER_ERROR:
                $this->handle(new PhackpRuntimeException("Fatal error on line $errline in file $errfile", E_USER_ERROR, $this->exceptions));
                $this->getErrorHandler()->fatal($this->exceptions);
                break;

            case E_USER_WARNING:
                $this->handle(new PhackpRuntimeException("Warning " . [$errno] . $errstr, E_USER_WARNING, $this->exceptions));
                $this->getErrorHandler()->warning($this->exceptions);
                break;

            case E_USER_NOTICE:
                $this->handle(new PhackpRuntimeException("Notice" . [$errno] . $errstr, E_USER_WARNING, $this->exceptions));
                $this->getErrorHandler()->notice($this->exceptions);
                break;

            default:
                $this->handle(new PhackpRuntimeException("Unknown error type: " [$errno] . $errstr, E_USER_WARNING, $this->exceptions));
                $this->getErrorHandler()->unknown($this->exceptions);
                break;
        }

        /* Don't execute PHP internal error handler */
        return true;
    }

    public function exceptionHandler(\Throwable $exception) {
        $this->handle($exception);
        $this->getExceptionHandler()->display($this->exceptions);
    }


}