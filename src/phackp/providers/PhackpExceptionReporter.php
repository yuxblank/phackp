<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 12/03/2017
 * Time: 17:22
 */

namespace yuxblank\phackp\providers;


use yuxblank\phackp\api\ExceptionHandlerReporter;
use yuxblank\phackp\core\Application;
use yuxblank\phackp\core\Router;

class PhackpExceptionReporter implements ExceptionHandlerReporter
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