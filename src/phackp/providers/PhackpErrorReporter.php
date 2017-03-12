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
    public function fatal(array $throwable)
    {
        $this->draw($throwable);
    }

    public function warning(array $throwable)
    {
        $this->draw($throwable);
    }

    public function notice(array $throwable)
    {
        $this->draw($throwable);
    }

    public function unknown(array $throwable)
    {
        $this->draw($throwable);
    }


    private function draw($throwable){
        /** @var \throwable $ex */
        foreach ($throwable as $ex) {
            echo "<p> error" . $ex->getMessage() ." on line:" . $ex->getLine(); "</p>";

        }
    }


}