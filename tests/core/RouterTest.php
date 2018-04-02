<?php

use yuxblank\phackp\http\HttpKernel;
use yuxblank\phackp\routing\exception\RouterException;
use yuxblank\phackp\routing\Router;

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 04/05/2016
 * Time: 12:30
 */
final class RouterTest extends PHPUnit_Framework_TestCase
{
    private $APP_URL;
    /** @var  Router */
    private $router;
    /** @var  HttpKernel */
    private $httpKernel;
    private $config;
    public $routes;

    protected function setUp()
    {
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../config/";
        $this->config = require $path."app.php";
        $this->routes = require $path."routes.php";
        $this->APP_URL = $this->config['app.globals']['APP_URL'];
        $this->httpKernel = new HttpKernel([$this->config['app.http']]);
        $this->router = new Router($this->routes['routes'], $this->config['app.globals'], $this->httpKernel->getRequest());
    }

    public function testLink()
    {
        $url = $this->APP_URL . '/test_link/1';
        $route = '{title}/{id}';
        $this->assertEquals($url, $this->router->link($route, array('test_link', 1)));
    }

    public function testFindRouteByAlias()
    {
        $this->assertEquals($this->router->alias('test.get', [1], 'GET'), $this->APP_URL . '/blog/title/1');
    }

    public function testFindAction()
    {
        $request = $this->httpKernel->getRequest();
        $request = $request->withUri(new \Zend\Diactoros\Uri("/blog/title/1"));
        $this->router = new Router($this->routes['routes'], $this->config['app.globals'], $request);

        $route = $this->router->findAction();

        $this->assertEquals($route->getClass(), \test\controller\App::class);
        $this->assertEquals($route->getAction(), "testGet");
    }

    /**
     * @expectedException \yuxblank\phackp\routing\exception\RouterException
     */
    public function testNotFound()
    {
        $request = $this->httpKernel->getRequest();
        $request = $request->withUri(new \Zend\Diactoros\Uri("/this/route/does/not/exist/404"));
        $this->router = new Router($this->routes['routes'], $this->config['app.globals'], $request);
        $route = $this->router->findAction();
    }


    public function testSupaDupaPathParams(){
        $request = $this->httpKernel->getRequest();
        $request = $request->withUri(new \Zend\Diactoros\Uri("/supa/dupa/1/and/key3/key3/supa/dupa/4/5key"));
        $this->router = new Router($this->routes['routes'], $this->config['app.globals'], $request);
        $route = $this->router->findAction();

        $this->assertEquals($route->getAlias(),"test.supadupa!");

    }

}
