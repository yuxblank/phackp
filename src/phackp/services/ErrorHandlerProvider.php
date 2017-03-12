<?php
namespace yuxblank\phackp\services;
use yuxblank\phackp\api\ErrorHandlerReporter;
use yuxblank\phackp\api\ThrowableHandler;
use yuxblank\phackp\core\ServiceProvider;
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
    protected $delegate;

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

    public function delegate(ErrorHandlerReporter $errorHandlerReporter)
    {
        $this->delegate = $errorHandlerReporter;

    }

    public function exclude(\Throwable $throwable)
    {
        $this->excluded[] = $throwable;
    }

    private function report(){
        if (!$this->delegate){
            throw new ServiceProviderException(ErrorHandlerProvider::class . " delegate was not set, cannot provide report!");
        }
        return $this->delegate->report($this->exceptions);
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
                $this->report();
                break;

            case E_USER_WARNING:
                $this->handle(new PhackpRuntimeException("Warning " . [$errno] . $errstr, E_USER_WARNING, $this->exceptions));
                break;

            case E_USER_NOTICE:
                $this->handle(new PhackpRuntimeException("Notice" . [$errno] . $errstr, E_USER_WARNING, $this->exceptions));
                break;

            default:
                $this->handle(new PhackpRuntimeException("Unknown error type: " [$errno] . $errstr, E_USER_WARNING, $this->exceptions));
                break;
        }

        /* Don't execute PHP internal error handler */
        return true;
    }

    public function exceptionHandler(\Throwable $exception) {
        $this->handle($exception);
    }


}