<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 12/03/2017
 * Time: 17:22
 */

namespace yuxblank\phackp\providers;


use Psr\Http\Message\ServerRequestInterface;
use yuxblank\phackp\core\Application;
use yuxblank\phackp\core\Router;
use yuxblank\phackp\services\api\ExceptionHandler;
use Zend\Diactoros\ServerRequest;

/**
 * Class PhackpExceptionHandler
 * Default framework ExceptionHandler delegate.
 * @package yuxblank\phackp\providers
 */
class PhackpExceptionHandler implements ExceptionHandler
{
    /**
     * This method is fired by the ErrorHandlerProvider on uncaught exception thrown
     * Will fire ERROR route 500 if exist or produce an HTML template with stacktrace
     * @param array $throwable
     * @return mixed|void
     */

    protected $router;
    protected $serverRequest;
    /**
     * Todo make DI working
     * PhackpExceptionHandler constructor.
     * @param $router
     */
    public function __construct(Router $router, ServerRequestInterface $serverRequest)
    {
        $this->router = $router;
        $this->serverRequest = $serverRequest;
    }


    public function onException(array $throwable)
    {
        if (($route = $this->router->getErrorRoute(500))!==null){

            $this->router->doRoute($route, $throwable, $this->serverRequest);

        } else {
            foreach ($throwable as $ex) {
                echo "<p>" . $ex->getMessage();
                "</p>";
            }
        }
    }


}