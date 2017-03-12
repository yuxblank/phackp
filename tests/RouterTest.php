<?php
require '../vendor/autoload.php';
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 04/05/2016
 * Time: 12:30
 */
use yuxblank\phackp\core\Router;

class RouterTest extends PHPUnit_Framework_TestCase
{
    const APP_URL = 'http://test.com';
    public $routes =
        [
        'GET' => [],
        'POST' =>[]
        ];

    public function testLink(){
        $url = 'http://test.com/test_link/1';
        $route ='{title}/{id}';
        self::assertEquals($url,self::APP_URL.Router::link($route, array('test_link',1)));
    }



}
