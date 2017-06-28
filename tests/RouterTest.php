<?php
require '../vendor/autoload.php';
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 04/05/2016
 * Time: 12:30
 */
use yuxblank\phackp\core\HttpKernel;
use yuxblank\phackp\core\Router;

final class RouterTest extends PHPUnit_Framework_TestCase
{
    private $APP_URL;
    /** @var  Router */
    private $router;
    private $httpKernel;
    private $config;
    public $routes;

    protected function setUp() {
        $this->config = require "config/app.php";
        $this->routes = require "config/routes.php";
        $this->APP_URL = $this->config['app.globals']['APP_URL'];
        $this->httpKernel = new HttpKernel([$this->config['app.http']]);
        $this->router = new Router($this->routes['routes'], $this->config['app.globals'], $this->httpKernel->getRequest());
    }

    public function testLink(){
        $url = $this->APP_URL . '/test_link/1';
        $route ='{title}/{id}';
        $this->assertEquals($url,$this->router->link($route, array('test_link',1)));
    }

    public function testFindRouteByAlias(){
        $this->assertEquals($this->router->alias('blogpost','GET', [1]), $this->APP_URL. '/blog/title/1' );
    }



}
