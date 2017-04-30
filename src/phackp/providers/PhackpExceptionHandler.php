<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 12/03/2017
 * Time: 17:22
 */

namespace yuxblank\phackp\providers;


use yuxblank\phackp\api\ApplicationController;
use yuxblank\phackp\core\Router;
use yuxblank\phackp\services\api\ExceptionHandler;

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

    /**
     * PhackpExceptionHandler constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    public function onException(ApplicationController $instance=null, string $method=null, array $throwable)
    {
        if ($instance!==null){
            $this->router->doRoute($instance, $method, $throwable);
        } else {
            foreach ($throwable as $ex) {
                echo "<p>" . $ex->getMessage();
                "</p>";
            }
        }
    }


}