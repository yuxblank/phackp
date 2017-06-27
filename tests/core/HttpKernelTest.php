<?php

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 27/06/2017
 * Time: 16:40
 */
class HttpKernelTest extends PHPUnit_Framework_TestCase
{
    /** @var  \yuxblank\phackp\core\HttpKernel */
    private $httpKernel;
    private $config;

    public function setUp(){
        $this->config = require '../config/app.php';
        $this->httpKernel = new \yuxblank\phackp\core\HttpKernel($this->config['app.http']);
    }

    public function testInit(){
        $this->assertNotNull($this->httpKernel->getRequest());
    }

    public function testQueryParam(){
        $req = $this->httpKernel->getRequest()->withQueryParams(['test' => '1']);
        $this->assertArrayHasKey('test', $req->getQueryParams());
    }


}