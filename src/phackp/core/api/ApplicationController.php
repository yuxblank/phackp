<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 30/04/2017
 * Time: 16:30
 */

namespace yuxblank\phackp\core\api;


interface ApplicationController extends EventDrivenController
{

    const EVENT_ON_BEFORE = 'onBefore';
    const EVENT_ON_AFTER = 'onAfter';
    /**
     * Keep a value for a next context, ex. via cookies
     * @param $name
     * @param $value
     * @param null $expire
     * @return
     */
    public function keep($name,$value,$expire=null);

}