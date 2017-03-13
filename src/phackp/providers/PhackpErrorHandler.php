<?php
namespace yuxblank\phackp\providers;

use yuxblank\phackp\services\api\ErrorHandler;

/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 14/12/2016
 * Time: 00:08
 */
class PhackpErrorHandler implements ErrorHandler
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