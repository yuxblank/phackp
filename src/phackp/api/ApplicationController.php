<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 30/04/2017
 * Time: 16:30
 */

namespace yuxblank\phackp\api;


interface ApplicationController extends EventDrivenController
{
    /**
     * Keep a value for a next context, ex. via cookies
     * @param $name
     * @param $value
     * @param null $expire
     * @return
     */
    public function keep($name,$value,$expire=null);

}