<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 04/03/18
 * Time: 18.24
 */

use test\tools\TestModule;

class ModuleTest extends PHPUnit_Framework_TestCase
{

    /** @var \yuxblank\phackp\core\Application */
    private $app;
    public function setUp()
    {
        $this->app = \yuxblank\phackp\core\Application::getInstance();
    }


    /** @runInSeparateProcess   */
    public function testAddModule(){
        $module = new TestModule();
        $app_path = defined("CONFIG_PATH") ? CONFIG_PATH : "./../config";
        $real = str_replace("/config","",$app_path);
        $this->app->bootstrap($real);
        $this->app->addModule($module);
        $this->app->run();

        $routes = $this->app->container()->get('routes');

        foreach ($routes as $route) {
            foreach ($route as $realRoute){
                if ($realRoute === $module->getRoutes()['GET'][0]){

                }
            }
        }

    }
}