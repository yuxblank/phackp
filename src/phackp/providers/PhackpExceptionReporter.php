<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 12/03/2017
 * Time: 17:22
 */

namespace yuxblank\phackp\providers;


use yuxblank\phackp\api\ExceptionHandlerReporter;

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