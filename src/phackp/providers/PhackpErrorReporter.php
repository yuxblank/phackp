<?php
namespace yuxblank\phackp\providers;
use yuxblank\phackp\api\ErrorHandlerReporter;

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
        foreach ($throwable as $ex){
            echo "<p>" . $ex->getMessage(); "</p>";
        }
    }


}