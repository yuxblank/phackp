<?php
namespace yuxblank\phackp\services;

use yuxblank\phackp\core\ServiceProvider;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\providers\PhackpExceptionHandler;
use yuxblank\phackp\services\api\ErrorHandler;
use yuxblank\phackp\services\api\ExceptionHandler;
use yuxblank\phackp\services\api\ServiceConfig;
use yuxblank\phackp\services\api\ThrowableHandler;
use yuxblank\phackp\services\configuration\ErrorHandlerConfig;
use yuxblank\phackp\services\exceptions\ServiceProviderException;
use yuxblank\phackp\utils\ReflectionUtils;

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
    /** @var  ErrorHandler */
    /*   protected $errorDelegate;*/
    /** @var  ExceptionHandler */
    protected $exceptionDelegate;

    /**
     * ErrorHandlerProvider constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function bootstrap()
    {
        /* set_error_handler(array($this, 'errorHandler'), E_ALL);*/ // todo
        if ($this->getConfig('exception_handler_enable') === true) {
            set_exception_handler(array($this, 'exceptionHandler'));
        }
        $excClazz = $this->getConfig('exception_handler_delegate');
        try {
            $this->exceptionDelegate = ReflectionUtils::makeInstance($excClazz);
            if (!class_implements($this->exceptionDelegate, ExceptionHandler::class)){
                throw new ServiceProviderException('The class '.$this->getConfig('exception_handler_delegate')
                    .' provided in configuration does not implements ' . ExceptionHandler::class, ServiceProviderException::INVALID_CONFIG);
            }
        } catch (InvocationException $ex) {
            throw new InvocationException('Class not found ' . $excClazz, InvocationException::SERVICE);
        }
    }


    public function defaultConfig(): array
    {
        return [
            'exception_handler_enable' => true,
            'exception_handler_delegate' => PhackpExceptionHandler::class,
        ];

    }

    public function isValidConfig()
    {
        return $this->getConfig("exception_handler_enable") != null && $this->getConfig("exception_handler_delegate") != null;
    }


    public function handle(\Throwable $throwable)
    {
        $this->exceptions[] = $throwable;
    }

    public function exclude(\Throwable $throwable)
    {
        $this->excluded[] = $throwable;
    }

    /*  private function getErrorHandler():ErrorHandlerReporter{
          if (!$this->errorDelegate){
              $this->errorDelegate(new PhackpErrorReporter()); // set default
          }
          return $this->errorDelegate;
      }*/


    /*    public function errorHandler(int $errno, string $errstr, $errfile, $errline)
        {
            if (!(error_reporting() & $errno)) {
                // This error code is not included in error_reporting, so let it fall
                // through to the standard PHP error handler
                return false;
            }


            switch ($errno) {
                case E_USER_ERROR:
                    $this->handle(new PhackpRuntimeException("Fatal error on line $errline in file $errfile", E_USER_ERROR));
                    $this->getErrorHandler()->fatal($this->exceptions);
                    break;

                case E_USER_WARNING:
                    $this->handle(new PhackpRuntimeException("Warning " . [$errno] . $errstr, E_USER_WARNING));
                    $this->getErrorHandler()->warning($this->exceptions);
                    break;

                case E_USER_NOTICE:
                    $this->handle(new PhackpRuntimeException("Notice" . [$errno] . $errstr, E_USER_NOTICE));
                    $this->getErrorHandler()->notice($this->exceptions);
                    break;

                default:
                    $this->handle(new PhackpRuntimeException("Unknown error type: " [$errno] . $errstr));
                    $this->getErrorHandler()->unknown($this->exceptions);
                    break;
            }

            return true;
        }*/

    public function exceptionHandler(\Throwable $exception)
    {
        $this->handle($exception);
        $this->exceptionDelegate->onException($this->exceptions);
    }


}