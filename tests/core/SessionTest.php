<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 10/05/2016
 * Time: 15:42
 */
use yuxblank\phackp\core\Application;
use yuxblank\phackp\core\Session;
@session_start();

class SessionTest extends PHPUnit_Framework_TestCase
{

    /** @var  Session */
    private $session;
    private $config;


    protected function setUp(){
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../config/";
        $this->config = require $path.'app.php';
        $this->session = new Session($this->config['app.session']);
    }

    public function testInstance()
    {

        $this->session->setValue('test', [1,2,3,4,5,6=>[new stdClass()]]);
        $this->assertNotNull($this->session->getValue('test'));
    }


}
