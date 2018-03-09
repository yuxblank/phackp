<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 04/03/18
 * Time: 0.33
 */

namespace yuxblank\phackp\core;


interface Module
{
    
    public function registerRoutes(array $routes);
    public function install();
    public function uninstall();




}