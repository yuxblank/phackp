<?php
namespace yuxblank\phackp\providers;
use yuxblank\phackp\api\ErrorHandlerReporter;
use yuxblank\phackp\api\EventDrivenController;
use yuxblank\phackp\core\Application;
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

            try {
                $controller = ReflectionUtils::makeInstance($route['class']);
            } catch (InvocationException $e) {
                http_response_code(404);
                throw new InvocationException('Class ' . $route['class'] . ' not found in routes', InvocationException::ROUTER);
                //die(Application::isDebug() ? $e : "");
            }

            $reflectionClass = new \ReflectionClass($controller);
            $eventDriven = $reflectionClass->implementsInterface(EventDrivenController::class);
            if ($eventDriven) {
                ReflectionUtils::invoke($controller, 'onBefore');
            }
            try {
                $controller->{$route['method']}($throwable);
            } catch (InvocationException $ex){
                throw new InvocationException('Method '. $route['method'] .' not found for error route class ' . $reflectionClass->getName(), InvocationException::ROUTER);
            }
            if ($eventDriven){
                ReflectionUtils::invoke($controller, 'onAfter');
            }

        } else {
            foreach ($throwable as $ex) {
                echo "<p>" . $ex->getMessage();
                "</p>";
            }
        }
    }


}