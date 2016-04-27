<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 27/04/2016
 * Time: 16:18
 */

namespace yuxblank\phackp\api;


interface EventDrivenController
{
    /**
     * This method will run before any other method and right after constructor.
     * @return void
     */
    public function before();
    /**
     * This method will run at last.
     * @return void
     */
    public function after();

}