<?php
namespace yuxblank\phackp\providers;
use yuxblank\phackp\api\ErrorHandlerReporter;
use yuxblank\phackp\api\EventDrivenController;
use yuxblank\phackp\core\Application;
use yuxblank\phackp\core\Router;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\utils\ReflectionUtils;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:08
 */
class PhackpErrorReporter implements ErrorHandlerReporter
{
    public function display(array $throwable)
    {

        if (($route = Application::getErrorRoute(500))!=null){

          Router::doRoute($route, $throwable);

        } else {
            foreach ($throwable as $ex) {
                echo "<p>" . $ex->getMessage();
                "</p>";
            }
        }
    }


}